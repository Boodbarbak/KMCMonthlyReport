<?php
class odoo_account_category{
	public $categories;
	public $categoriesByAccount;
	public $categoriesByLevel;
	
	public $id;			// Id of the products category to merge with
	public $name;
	public $parent;
	public $level=0;
	public $path='/ ';
	public $fullpath;
	
	public $type;		// 'in', 'out' or null
	
	public $accounts=array();
	
	public $rows;
	
	public $sales;		// Total sales in the category
	public $purchases;	// Total purchases in the category
	public $salesByPayment;		// Total sales based on payment period in the category
	public $purchasesByPayment;	// Total purchases based on payment period in the category
	
	public function __construct(array $categories, array $categoriesByAccount, array $categoriesById, array $categoriesByLevel, array $obj=array()){
		$this->sales = new odoo_product_sales();
		$this->purchases = new odoo_product_sales();
		$this->salesByPayment = new odoo_product_sales();
		$this->purchasesByPayment = new odoo_product_sales();
		
		$this->categories = $categories;
		$this->categoriesByAccount = $categoriesByAccount;
		$this->categoriesByLevel = $categoriesByLevel;
		
		$this->name = $obj['name'];
		if(isset($obj['parent'])){
			$this->parent = $obj['parent'];
			$this->level = $this->parent->level+1;
			$this->path = $this->parent->fullpath.' / ';
		}
		$this->fullpath = $this->path.$this->name;
		
		if(!isset($this->categoriesByLevel[$this->level]))
			$this->categoriesByLevel[$this->level] = array();
		$this->categoriesByLevel[$this->level][] = $this;
		
		$this->type = $obj['type'];
		
		if(isset($obj['account'])){
			$this->accounts = is_array($obj['account'])?$obj['account']:array($obj['account']);
			foreach($this->accounts as $account)
				$this->categoriesByAccount[$account] = $this;
		}
		
		if(isset($obj['id']))
			$this->id = $obj['id'];	// this means this category has to be merged with the products category with this id
		else
		// If parent category has to be merged with an existing products category
		if($this->parent && $this->parent->id){
			// Place this category has a child of the product category in the categories list
			$index = array_search($categoriesById[$this->parent->id], $this->categories, TRUE);	// Get the index of the product category in the categories list
			array_splice($this->categories, $index+1, 0, array($this));	// Place the category
		}
		else
			$this->categories[] = $this;	// Adding the category to the list of the categories to show in the report only if it has not to be merged with an existing products category
		
		if(isset($obj['children']) && is_array($obj['children'])){
			foreach($obj['children'] as $child){
				if(!$this->id)
					$child['parent'] = $this;
				else
					$child['parent'] = $categoriesById[$this->id];
				$cat = new odoo_account_category($this->categories, $this->categoriesByAccount, $categoriesById, $this->categoriesByLevel, $child);
				$this->categories = $cat->categories;
				$this->categoriesByAccount = $cat->categoriesByAccount;
				$this->categoriesByLevel = $cat->categoriesByLevel;
			}
		}
	}
	
	public function add($data){
		if(is_array($data)){
			$quantity = $data['quantity'];
			$amount = $data['total'];
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
		}
		else{
			$object = $data;
			
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
}
