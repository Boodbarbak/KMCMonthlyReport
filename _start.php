<?php
// Load config
include 'includes/config.php';

// Load libs
include 'includes/classes/odoo_account_periods.class.php';
include 'includes/classes/odoo_account_period.class.php';
include 'includes/classes/odoo_product_categories.class.php';
include 'includes/classes/odoo_product_category.class.php';

// Connect to DB
try {
	$GLOBALS['odooDb'] = new PDO(
		$GLOBALS['config']['odooDb']['dsn'],
		$GLOBALS['config']['odooDb']['user'],
		$GLOBALS['config']['odooDb']['password']
		);
}
catch(PDOException $e) {
	echo 'Connection to OdooDb failed: '.$e->getMessage();
	exit;
}
