<?php
// KMC Monthly Financial Report Script configuration file

// *** CSV files details ***
$_GLOBAL['config']['csv']['path'] = '';
$_GLOBAL['config']['csv']['url'] = '';
$_GLOBAL['config']['csv']['delimiter'] = ',';
$_GLOBAL['config']['csv']['enclosure'] = '"';
$_GLOBAL['config']['csv']['escapeChar'] = '\\';


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

$_GLOBAL['config']['odooDb'] = $prodDb;
$_GLOBAL['config']['odooDb'] = $devDb;	// comment this line to use production database

$_GLOBAL['config']['odooDb']['dsn'] = 'pgsql:dbname='.$_GLOBAL['config']['odooDb']['db'].';'
	.'host='.$_GLOBAL['config']['odooDb']['ip'].';'
	.'port='.$_GLOBAL['config']['odooDb']['port'].';'
	;

// *** KMC Monthly Financial Report configuration ***
// Show products of the following categories

// Hide children (categories AND products) of the following categories

// Show analytic of the following categories

// Hide the following categories

// Incomes bank account

// Expenditures bank account

// Departments computed from accounting

