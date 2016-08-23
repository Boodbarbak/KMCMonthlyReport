<?php
class odoo_pos_products_sales{
	private $db;
	
	public $periods;
	public $productsSales;
	public $productsSalesById;

	public function __construct($db, array $periods=array(), array $productsSales=array()){
		$this->db = $db;
				
		$this->productsSales = $this->productsSalesById = $productsSales;
		
		if(count($periods))
		{
			$this->periods = $periods;
			$this->getProductsSales();
		}
	}
	
	public function getProductsSales(){
		$sql = 'SELECT 
				pt.*, 
				SUM(pol.qty) AS qty_total,
				SUM(pol.price_subtotal) AS price_wovat_total,
				SUM(pol.price_subtotal_incl) AS price_wvat_total
			FROM
				pos_order_line AS pol
				JOIN pos_order AS po ON pol.order_id=po.id
				JOIN account_move AS am ON po.account_move=am.id
				JOIN product_template AS pt ON pol.product_id=pt.id
			WHERE
				am.period_id IN ('.implode($this->periods,',').')
			GROUP BY pt.id
		';
		
		foreach($this->db->query($sql) as $row){
			$obj = new odoo_product_sales($this, $row);
		}
	}
}
