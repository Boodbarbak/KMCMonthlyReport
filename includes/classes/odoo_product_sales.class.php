<?php
class odoo_product_sales{
	private $productsSales;
	
	public $rows=array();
	
	public $product;		// Details of the product as an odoo_product object
	public $quantity;		// Quantity sold
	public $totalWTaxes;	// Total amount of sales with taxes
	public $totalWOTaxes;	// Total amount of sales without taxes
	
	public function __construct($productsSales, array $row=array()){
		$this->productsSales = $productsSales;
		$this->rows[] = $row;
		
		if(count($row)){
			$this->quantity = $row['qty_total'];
			$this->totalWTaxes = $row['price_wvat_total'];
			$this->totalWOTaxes = $row['price_wovat_total'];
			
			if(isset($row['name'])){
				$this->product = new odoo_product($row);
			}
			
			$this->productsSales->addProductSales($this);
			
			$this->productsSales->productCategories->categoriesById[$this->product->categoryId]->addProductSales($this);
		}
	}
}
