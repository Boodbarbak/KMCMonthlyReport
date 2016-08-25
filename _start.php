<?php
// Load config
include 'includes/config.php';

// Load libs
require_once 'includes/functions.php';
require_once 'includes/classes/odoo_account_periods.class.php';
require_once 'includes/classes/odoo_account_period.class.php';
require_once 'includes/classes/odoo_invoices_products.class.php';
require_once 'includes/classes/odoo_pos_products_sales.class.php';
require_once 'includes/classes/odoo_product_categories.class.php';
require_once 'includes/classes/odoo_product_category.class.php';
require_once 'includes/classes/odoo_product.class.php';
require_once 'includes/classes/odoo_product_sales.class.php';

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
