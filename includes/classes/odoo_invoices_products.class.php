<?php
require_once 'odoo_pos_products_sales.class.php';

class odoo_invoices_products extends odoo_pos_products_sales{
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
				JOIN product_template AS pt ON p.product_tmpl_id=pt.id
			WHERE
				i.state != \'draft\'
				AND i.period_id IN ('.implode($this->periods,',').')
			GROUP BY pt.id, i.type, t.amount
		';
		
		foreach($this->db->query($sql) as $row){
			$obj = new odoo_product($row);
			
			$this->addProductSales($obj);
			
			$this->productCategories->categoriesById[$obj->categoryId]->addProductSales($obj);
		}
	}
}
