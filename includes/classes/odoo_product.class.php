<?php
class odoo_product{
	public $id;
	public $name;
	public $categoryId;

	public function __construct(array $row=array()){
		if(count($row))
		{
			$this->id = $row['id'];
			$this->name = $row['name'];
			$this->categoryId = $row['categ_id'];
		}
	}
}
