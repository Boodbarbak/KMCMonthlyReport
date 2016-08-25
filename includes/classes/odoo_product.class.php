<?php
class odoo_product{
	public $sales;
	public $purchases;
	public $rows=array();

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
}
