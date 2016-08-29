<?php
class odoo_pos_products_sales{
	protected $db;
	
	public $periods;
	public $products;
	public $productCategories;
	
	public $sales;		// Total sales
	public $purchases;	// Total purchases
	public $salesByPayment;		// Total sales based on payment period
	public $purchasesByPayment;	// Total purchases based on payment period

	public function __construct($db, $productCategories, array $periods=array(), array $productsSales=array()){
		$this->db = $db;
		$this->sales = new odoo_product_sales();
		$this->purchases = new odoo_product_sales();
		$this->salesByPayment = new odoo_product_sales();
		$this->purchasesByPayment = new odoo_product_sales();
				
		$this->products = $productsSales;
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
				am.period_id IN ('.implode(',',$this->periods).')
			GROUP BY pt.id
		';
		
		foreach($this->db->query($sql) as $row){
			$obj = new odoo_product($row);
			// On POS, payments are always at the same period.
			// So, adds sales to by payments sales
			$obj->salesByPayment->quantity = $obj->sales->quantity;
			$obj->salesByPayment->totalWTaxes = $obj->sales->totalWTaxes;
			$obj->salesByPayment->totalWOTaxes = $obj->sales->totalWOTaxes;
			
			// It is not possible to have purchases in POS. So no need to do the same with purchases.
			
			$this->addProductSales($obj);	// Adds the sales of the product to the products list
			
			// Add the sales of the products to its category
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
			$this->products[$product->id]->add($product);
		}
		
		$this->add($product);
	}
	
	public function add($object){
		$this->sales->quantity += $object->sales->quantity;
		$this->sales->totalWOTaxes += $object->sales->totalWOTaxes;
		$this->sales->totalWTaxes += $object->sales->totalWTaxes;
		
		$this->purchases->quantity += $object->purchases->quantity;
		$this->purchases->totalWOTaxes += $object->purchases->totalWOTaxes;
		$this->purchases->totalWTaxes += $object->purchases->totalWTaxes;
		
		$this->salesByPayment->quantity += $object->salesByPayment->quantity;
		$this->salesByPayment->totalWTaxes += $object->salesByPayment->totalWTaxes;
		$this->salesByPayment->totalWOTaxes += $object->salesByPayment->totalWOTaxes;
		
		$this->purchasesByPayment->quantity += $object->purchasesByPayment->quantity;
		$this->purchasesByPayment->totalWTaxes += $object->purchasesByPayment->totalWTaxes;
		$this->purchasesByPayment->totalWOTaxes += $object->purchasesByPayment->totalWOTaxes;
	}
}
