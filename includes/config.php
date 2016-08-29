<?php
// KMC Monthly Financial Report Script configuration file

// *** CSV files details ***
$GLOBALS['config']['csv']['path'] = 'reports/';
$GLOBALS['config']['csv']['url'] = 'reports/';
$GLOBALS['config']['csv']['delimiter'] = ',';
$GLOBALS['config']['csv']['enclosure'] = '"';
$GLOBALS['config']['csv']['escapeChar'] = '\\';


// *** Database details ***
// production database details
$prodDb = array(
	'ip'		=>	'192.168.1.3',
	'port'		=>	'5432',
	'db'		=>	'cmk',
	'user'		=>	'postgres',
	'password'	=>	'postgres',	
	);
	
// developement database details
$devDb = array(
	'ip'		=>	'127.0.0.1',
	'port'		=>	'5432',
	'db'		=>	'cmk',
	'user'		=>	'cmk',
	'password'	=>	'cmk',	
	);

$GLOBALS['config']['odooDb'] = $prodDb;
$GLOBALS['config']['odooDb'] = $devDb;	// comment this line to use production database

$GLOBALS['config']['odooDb']['dsn'] = 'pgsql:dbname='.$GLOBALS['config']['odooDb']['db'].';'
	.'host='.$GLOBALS['config']['odooDb']['ip'].';'
	.'port='.$GLOBALS['config']['odooDb']['port'].';'
	;

// *** KMC Monthly Financial Report configuration ***
// Odoo Companies Id
$GLOBALS['config']['odoo']['companies'] = array(1,3);	// 1 = CMKFR/TharpaFR; 3 = IRCB

// Departments computed from accounting
$accountCats = array();
$accountCats[] = array(
	'name'		=> 'Taxes',
	'type'		=> 'in',	// 'in' is for expenditure, 'out' for incomes
	'children'	=> array(
		array('name'=>'General taxes', 'account' => '637200', 'type' => 'in'),
//		array('name'=>'Taxe de séjour', 'account' => '637200', 'type' => 'in'),	// FIXME Same account as 'General taxes' !?
		array('name'=>'Côtisation Foncière', 'account' => '635110', 'type' => 'in'),
		array('name'=>'Taxes Foncières', 'account' => '635120', 'type' => 'in'),
		array('name'=>'Impôt sur les Sociétés', 'account' => '635100', 'type' => 'in'),
	),
);
$accountCats[] = array(
	'name'		=> 'Sponsorship',
	'type'		=> 'in',	// 'in' is for expenditure, 'out' for incomes
	'id'		=> 52,		// if it as too be added to an existing products category, id of this products category
	'children'	=> array(
		array('name'=>'Monthly Stipends', 'account' => array('641000', '641100'), 'type' => 'in'),
		array('name'=>'URSSAF', 'account' => '645100', 'type' => 'in'),
	),
);
// TODO Exclude Tharpa Sponsorship and include it in another category (use analytic?)
$GLOBALS['config']['odoo']['accountCategories'] = $accountCats;

// Show products of the following categories

// Hide children (categories AND products) of the following categories

// Show analytic of the following categories

// Hide the following categories

// Incomes bank account

// Expenditures bank account

