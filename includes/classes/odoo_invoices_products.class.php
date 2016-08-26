<?php
require_once 'odoo_pos_products_sales.class.php';

class odoo_invoices_products extends odoo_pos_products_sales{
	public $byPaymentPeriod = false;
	
	public function __construct($db, $productCategories, array $periods=array(), array $productsSales=array(), $byPaymentPeriod=false){
		$this->byPaymentPeriod = $byPaymentPeriod;
		parent::__construct($db, $productCategories, $periods, $productsSales);
	}
	
	public function getProductsSales(){
		$sql = 'SELECT 
				pt.*,
				i.type, 
				SUM(il.quantity) AS qty_total,
				SUM(il.price_subtotal) AS price_wovat_total,
				SUM(il.price_subtotal+il.price_subtotal*COALESCE(t.amount,0)) AS price_wvat_total
			FROM
				account_invoice_line AS il
				JOIN account_invoice AS i ON il.invoice_id=i.id
				LEFT JOIN account_invoice_line_tax AS ilt ON il.id=ilt.invoice_line_id
				LEFT JOIN account_tax AS t ON ilt.tax_id=t.id
				JOIN product_product AS p ON il.product_id=p.id
				JOIN product_template AS pt ON p.product_tmpl_id=pt.id';
		if($this->byPaymentPeriod){
			// TODO this jointure (method..?) ignores that there can be :
			//   - several payments for one invoice (the invoice is multiplied by the number of  payments...)
			//   - invoices partially payd (it always acts like the invoice is totally paid)
			$sql .= '
				JOIN account_move_line AS ml ON i.move_id = ml.move_id
				JOIN account_move_line AS payment ON ml.reconcile_id = payment.reconcile_id AND ml.id != payment.id';
			}
		$sql .= '
			WHERE
				i.state != \'draft\'';
		if($this->byPaymentPeriod){
			$sql .= '
				AND payment.period_id IN ('.implode($this->periods,',').')';
		}
		else{
			$sql .= '
				AND i.period_id IN ('.implode($this->periods,',').')';
		}
		$sql .= '
			GROUP BY pt.id, i.type, t.amount';
		if($this->byPaymentPeriod){
			$sql .= ', payment.id';
		}
		
		foreach($this->db->query($sql) as $row){
			$obj = new odoo_product($row);
			
			if($this->byPaymentPeriod){
				$obj->salesByPayment = $obj->sales;
				$obj->purchasesByPayment = $obj->purchases;
				$obj->sales = new odoo_product_sales();
				$obj->purchases = new odoo_product_sales();
			}
			
			$this->addProductSales($obj);
			
			$this->productCategories->categoriesById[$obj->categoryId]->addProductSales($obj);
		}
	}
}
