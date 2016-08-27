<?php
class odoo_account_category{
	public $categories;
	public $categoriesByAccount;
	
	public $name;
	public $parent;
	public $level=0;
	public $path='/ ';
	public $fullpath;
	
	public $type;
	
	public $accounts=array();
	
	public $rows;
	
	public $sales;		// Total sales in the category
	public $purchases;	// Total purchases in the category
	public $salesByPayment;		// Total sales based on payment period in the category
	public $purchasesByPayment;	// Total purchases based on payment period in the category
	
	public function __construct(array $categories, array $categoriesByAccount, array $obj=array()){
		$this->sales = new odoo_product_sales();
		$this->purchases = new odoo_product_sales();
		$this->salesByPayment = new odoo_product_sales();
		$this->purchasesByPayment = new odoo_product_sales();
		
		$this->categories = $categories;
		$this->categoriesByAccount = $categoriesByAccount;
		
		$this->name = $obj['name'];
		if(isset($obj['parent'])){
			$this->parent = $obj['parent'];
			$this->level = $this->parent->level+1;
			$this->path = $this->parent->fullpath.' / ';
		}
		$this->fullpath = $this->path.$this->name;
		
		$this->type = $obj['type'];
		
		if(isset($obj['account'])){
			$this->accounts = is_array($obj['account'])?$obj['account']:array($obj['account']);
			foreach($this->accounts as $account)
				$this->categoriesByAccount[$account] = $this;
		}
		
		$this->categories[] = $this;
		
		if(isset($obj['children']) && is_array($obj['children'])){
			foreach($obj['children'] as $child){
				$child['parent'] = $this;
				$cat = new odoo_account_category($this->categories, $this->categoriesByAccount, $child);
				$this->categories = $cat->categories;
				$this->categoriesByAccount = $cat->categoriesByAccount;
			}
		}
	}
	
	public function add($quantity, $amount, $type=null){
		if($type === null)
			$type = $this->type;
			
		if($type == 'in'){
			$this->purchases->quantity += $quantity;
			$this->purchases->totalWTaxes += $amount;
			$this->purchases->totalWOTaxes += $amount;
			$this->purchasesByPayment->quantity += $quantity;
			$this->purchasesByPayment->totalWTaxes += $amount;
			$this->purchasesByPayment->totalWOTaxes += $amount;
		}
		else{
			$this->sales->quantity += $quantity;
			$this->sales->totalWTaxes += $amount;
			$this->sales->totalWOTaxes += $amount;
			$this->salesByPayment->quantity += $quantity;
			$this->salesByPayment->totalWTaxes += $amount;
			$this->salesByPayment->totalWOTaxes += $amount;
		}
		
		if(isset($this->parent))
			$this->parent->add($quantity, $amount, $type);
	}
}
