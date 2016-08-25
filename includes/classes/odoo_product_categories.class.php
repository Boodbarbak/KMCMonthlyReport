<?php
class odoo_product_categories{
	protected $db;	// PDO object
	
	public $categories;
	public $categoriesById;
	
	public function __construct($db){
		$this->db = $db;
		$this->get_categories();
	}
	
	public function get_categories(){
		// NOTE : Odoo parent_category uses a "Nested set Model". See https://en.wikipedia.org/wiki/Nested_set_model and http://mikehillyer.com/articles/managing-hierarchical-data-in-mysql/
	
		$this->categories = array();
		$this->categoriesById = array();
	
		$sql = 'SELECT node.* 
			FROM 
				product_category AS node, 
				product_category AS parent 
			WHERE 
				node.parent_left BETWEEN parent.parent_left AND parent.parent_right 
				AND parent.parent_id IS NULL 
			ORDER BY node.parent_left
			';
		foreach($this->db->query($sql) as $row){
			$category = new odoo_product_category($this, $row);
			$this->categories[] = $category;
			$this->categoriesById[$category->id] = $category;
		}
	}
}
