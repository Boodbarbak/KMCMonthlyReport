<?php
class odoo_pos_products_sales{
	protected $db;
	
	public $periods;
	public $productsSales;
	public $productsSalesById;
	public $productCategories;
	
	public $sales;		// Total sales
	public $purchases;	// Total purchases

	public function __construct($db, $productCategories, array $periods=array(), array $productsSales=array()){
		$this->db = $db;
		$this->sales = new odoo_product_sales();
		$this->purchases = new odoo_product_sales();
				
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
			$obj = new odoo_product($row);
			
			$this->addProductSales($obj);
			
			$this->productCategories->categoriesById[$obj->categoryId]->addProductSales($obj);
		}
	}
	
	// Add product sales to the total of sales in the category
	public function addProductSales(odoo_product $product){
		// If the product does not exist yet in the sales list
		if(!isset($this->products[$product->id])){
			// Add it to the sales list
			$this->products[$product->id] = $product;
		}
		else{	// If the product does already exist in the sales list
			// Add the sales and purchases quantities and amounts to the existing product in the list
			$sales = $this->products[$product->id]->sales;
			$sales->quantity += $product->sales->quantity;
			$sales->totalWTaxes += $product->sales->totalWTaxes;
			$sales->totalWOTaxes += $product->sales->totalWOTaxes;
			
			$purchases = $this->products[$product->id]->purchases;
			$purchases->quantity += $product->purchases->quantity;
			$purchases->totalWTaxes += $product->purchases->totalWTaxes;
			$purchases->totalWOTaxes += $product->purchases->totalWOTaxes;
		}
		
		$this->sales->quantity += $product->sales->quantity;
		$this->sales->totalWOTaxes += $product->sales->totalWOTaxes;
		$this->sales->totalWTaxes += $product->sales->totalWTaxes;
		
		$this->purchases->quantity += $product->purchases->quantity;
		$this->purchases->totalWOTaxes += $product->purchases->totalWOTaxes;
		$this->purchases->totalWTaxes += $product->purchases->totalWTaxes;
	}
}
