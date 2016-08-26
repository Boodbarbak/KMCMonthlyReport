<?php
class odoo_product{
	public $rows=array();

	public $sales;
	public $purchases;
	public $salesByPayment;
	public $purchasesByPayment;

	public $id;
	public $name;
	public $categoryId;

	public function __construct(array $row=array()){
		$this->rows[] = $row;

		if(count($row))
		{
			$this->id = $row['id'];
			$this->name = $row['name'];
			$this->categoryId = $row['categ_id'];
			$this->sales = new odoo_product_sales();
			$this->purchases = new odoo_product_sales();
			$this->salesByPayment = new odoo_product_sales();
			$this->purchasesByPayment = new odoo_product_sales();
			
			// If quantities
			if(isset($row['qty_total'])){
				// From a purchase invoice ?
				if(isset($row['type']) && substr($row['type'], 0, 3)=='in_') {
					$this->purchases = new odoo_product_sales($row);
				}
				else {
					$this->sales = new odoo_product_sales($row);
				}
			}
		}
	}
	
	public function add(odoo_product $product){
		$this->sales->quantity += $product->sales->quantity;
		$this->sales->totalWTaxes += $product->sales->totalWTaxes;
		$this->sales->totalWOTaxes += $product->sales->totalWOTaxes;
		
		$this->purchases->quantity += $product->purchases->quantity;
		$this->purchases->totalWTaxes += $product->purchases->totalWTaxes;
		$this->purchases->totalWOTaxes += $product->purchases->totalWOTaxes;
		
		$this->salesByPayment->quantity += $product->salesByPayment->quantity;
		$this->salesByPayment->totalWTaxes += $product->salesByPayment->totalWTaxes;
		$this->salesByPayment->totalWOTaxes += $product->salesByPayment->totalWOTaxes;
		
		$this->purchasesByPayment->quantity += $product->purchasesByPayment->quantity;
		$this->purchasesByPayment->totalWTaxes += $product->purchasesByPayment->totalWTaxes;
		$this->purchasesByPayment->totalWOTaxes += $product->purchasesByPayment->totalWOTaxes;
	}
}
