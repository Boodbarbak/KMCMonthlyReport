<?php
class odoo_account_period{
	protected $periods;
	
	public $id;
	public $code;
	public $name;
	public $companyId;
	
	public function __construct(odoo_account_periods $periods, array $row=array()){
		$this->periods = $periods;
		
		$this->id = $row['id'];
		$this->code = $row['code'];
		$this->name = $row['name'];
		$this->companyId = $row['company_id'];
	}
}
