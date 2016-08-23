<?php
class odoo_product_sales{
	private $productsSales;
	
	public $product;
	public $quantity;
	public $totalWTaxes;
	public $totalWOTaxes;
	
	public function __construct($productsSales, array $row=array()){
		$this->productsSales = $productsSales;
		
		if(count($row)){
			$this->quantity = $row['qty_total'];
			$this->totalWTaxes = $row['price_wvat_total'];
			$this->totalWOTaxes = $row['price_wovat_total'];
			
			if(isset($row['name'])){
				$this->product = new odoo_product($row);
			}
			
			if(!isset($this->productsSales->productsSales[$this->product->id])){
				$this->productsSales->productsSales[$this->product->id] = $this;
			}
			else{
				$this->productsSales->productsSales[$this->product->id]->quantity += $this->quantity;
				$this->productsSales->productsSales[$this->product->id]->totalWTaxes += $this->totalWTaxes;
				$this->productsSales->productsSales[$this->product->id]->totalWOTaxes += $this->totalWOTaxes;
			}
		}
	}
}
