<?php
class odoo_account_account{
	protected $db;
	
	public function __construct(PDO $db){
		$this->db = $db;
	}
	
	public function getAccountsTotal(array $accounts, array $periods){
		$sql = 'SELECT 
				a.code,
				COUNT(ml.id) as quantity,
				SUM(debit)-SUM(credit) as total
			FROM 
				account_move_line AS ml
				JOIN account_account AS a ON a.id = ml.account_id
			WHERE
				ml.period_id IN ('.implode(',',$periods).')
				AND a.code IN (\''.implode('\',\'',$accounts).'\')
			GROUP BY a.code
		';
		
		$result = array();
		foreach($this->db->query($sql) as $row){
			$result[$row['code']] = array('quantity'=>$row['quantity'], 'total'=>$row['total']);
		}
		
		return $result;
	}
}
