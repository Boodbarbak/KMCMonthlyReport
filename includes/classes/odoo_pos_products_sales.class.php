<?php
class odoo_pos_products_sales{
	private $db;
	
	public $periods;
	public $productsSales;
	public $productsSalesById;
	public $productCategories;
	
	public $quantity;		// Quantity sold
	public $totalWTaxes;	// Total amount of sales with taxes
	public $totalWOTaxes;	// Total amount of sales without taxes

	public function __construct($db, $productCategories, array $periods=array(), array $productsSales=array()){
		$this->db = $db;
				
		$this->productsSales = $this->productsSalesById = $productsSales;
		$this->productCategories = $productCategories;
		
		if(count($periods))
		{
			$this->periods = $periods;
			$this->getProductsSales();
		}
	}
	
	public function getProductsSales(){
		$sql = 'SELECT 
				pt.*, 
				SUM(pol.qty) AS qty_total,
				SUM(pol.price_subtotal) AS price_wovat_total,
				SUM(pol.price_subtotal_incl) AS price_wvat_total
			FROM
				pos_order_line AS pol
				JOIN pos_order AS po ON pol.order_id=po.id
				JOIN account_move AS am ON po.account_move=am.id
				JOIN product_product AS p ON pol.product_id=p.id
				JOIN product_template AS pt ON p.product_tmpl_id=pt.id
			WHERE
				am.period_id IN ('.implode($this->periods,',').')
			GROUP BY pt.id
		';
		
		foreach($this->db->query($sql) as $row){
			$obj = new odoo_product_sales($this, $row);
		}
	}
	
	// Add product sales to the total of sales
	public function addProductSales(odoo_product_sales $productSales){
		if(!isset($this->productsSales[$productSales->product->id])){
			$this->productsSales[$productSales->product->id] = $productSales;
		}
		else{
			$prod = $this->productsSales[$productSales->product->id];
			$prod->quantity += $productSales->quantity;
			$prod->totalWTaxes += $productSales->totalWTaxes;
			$prod->totalWOTaxes += $productSales->totalWOTaxes;
		}
		
		$this->quantity += $productSales->quantity;
		$this->totalWOTaxes += $productSales->totalWOTaxes;
		$this->totalWTaxes += $productSales->totalWTaxes;
	}
}
