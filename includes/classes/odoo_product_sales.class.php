<?php
class odoo_product_sales{
	public $rows=array();
	
	public $quantity;		// Quantity sold
	public $totalWTaxes;	// Total amount of sales with taxes
	public $totalWOTaxes;	// Total amount of sales without taxes
	
	public function __construct(array $row=array()){
		$this->rows[] = $row;
		
		if(count($row)){
			$this->quantity = $row['qty_total'];
			$this->totalWTaxes = $row['price_wvat_total'];
			$this->totalWOTaxes = $row['price_wovat_total'];
			
			if(isset($row['type']) && substr($row['type'], -6)=='refund'){
				$this->quantity *= -1;
				$this->totalWTaxes *= -1;
				$this->totalWOTaxes *= -1;
			}
		}
	}
}
