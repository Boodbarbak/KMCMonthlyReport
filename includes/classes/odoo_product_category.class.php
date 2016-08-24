<?php
class odoo_product_category{
	private $categories;	// odoo_product_categories object, used for categories processing, getting categories...
	
	public $id;			// Id of the category in the database
	public $name;		// Name of the category (non translated, aka english)
	public $left;		// Nested set model var
	public $right;		// Nested set model var
	public $parentId;	// Parent category id
	public $parent;		// Parent category as an odoo_product_category object
	public $level;		// Level of the category in the tree
	public $path;		// Path of the category in the tree (/ Parent level 1 / Parent level 2 /)
	public $fullpath;	// Path of the category in the tree, including the category itself (/ Parent level 1 / Parent level 2 / Category's name)
	public $productsSales;
	public $quantity;		// Total quantity of sold products in the category
	public $totalWTaxes;	// Total amount of sales with taxes in the category
	public $totalWOTaxes;	// Total amount of sales without taxes in the category
	
	public function __construct(odoo_product_categories $categories, array $row=array()){
		$this->categories = $categories;
		$this->productsSales = array();
		
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
