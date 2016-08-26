<?php
class odoo_product_category{
	protected $categories;	// odoo_product_categories object, used for categories processing, getting categories...
	
	public $id;			// Id of the category in the database
	public $name;		// Name of the category (non translated, aka english)
	public $left;		// Nested set model var
	public $right;		// Nested set model var
	public $parentId;	// Parent category id
	public $parent;		// Parent category as an odoo_product_category object
	public $level;		// Level of the category in the tree
	public $path;		// Path of the category in the tree (/ Parent level 1 / Parent level 2 /)
	public $fullpath;	// Path of the category in the tree, including the category itself (/ Parent level 1 / Parent level 2 / Category's name)
	
	public $products;	// List of products in the category
	
	public $sales;		// Total sales in the category
	public $purchases;	// Total purchases in the category
	public $salesByPayment;		// Total sales based on payment period in the category
	public $purchasesByPayment;	// Total purchases based on payment period in the category
	
	public function __construct(odoo_product_categories $categories, array $row=array()){
		$this->categories = $categories;
		$this->products = array();
		$this->sales = new odoo_product_sales();
		$this->purchases = new odoo_product_sales();
		$this->salesByPayment = new odoo_product_sales();
		$this->purchasesByPayment = new odoo_product_sales();
		
		if(count($row)){
			$this->id = $row['id'];
			$this->name = $row['name'];
			$this->left = $row['parent_left'];
			$this->right = $row['parent_right'];
			$this->parentId = $row['parent_id'];
			if($this->parentId){
				$this->parent = $categories->categoriesById[$this->parentId];
				$this->level = $this->parent->level+1;
				$this->path = $this->parent->fullpath.' / ';
			}
			else{
				$this->level = 0;
				$this->path = '/ ';
			}
			$this->fullpath = $this->path.$this->name;
		}
	}
	
	// Add product sales to the total of sales in the category
	public function addProductSales(odoo_product $product){
		// If the product does not exist yet in the sales list
		if(!isset($this->products[$product->id])){
			// Add it to the sales list
			$this->products[$product->id] = $product;
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
