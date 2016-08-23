<?php
class odoo_account_periods{
	private $db;
	
	public $periods;
	public $periodsById;
	public $periodsByCompany;

	public function __construct($db){
		$this->db = $db;
		
		$this->getPeriods();
	}
	
	public function getPeriods(){
		$this->periods = array();
		$this->periodsById = array();
		$this->periodsByCompany = array();
		
		foreach($GLOBALS['config']['odoo']['companies'] as $companyId){
			$this->periodsByCompany[$companyId] = array();
		}
		
		$sql = 'SELECT * 
			FROM account_period
			WHERE 
				company_id IN ('.implode($GLOBALS['config']['odoo']['companies'],',').')
			ORDER BY company_id, date_start, id
			';
		foreach($this->db->query($sql) as $row){
			$obj = new odoo_account_period($this, $row);
			$this->periods[] = $obj;
			$this->periodsById[$obj->id] = $obj;
			$this->periodsByCompany[$obj->companyId][] = $obj;
		}
	}

}
