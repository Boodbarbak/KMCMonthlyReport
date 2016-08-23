<?php
// KMC Monthly Financial Report Script configuration file

// *** CSV files details ***
$GLOBALS['config']['csv']['path'] = '';
$GLOBALS['config']['csv']['url'] = '';
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

// Show products of the following categories

// Hide children (categories AND products) of the following categories

// Show analytic of the following categories

// Hide the following categories

// Incomes bank account

// Expenditures bank account

// Departments computed from accounting

