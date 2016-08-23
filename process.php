<?php
// Load config
include 'includes/config.php';
include 'includes/classes/odoo_product_categories.class.php';
include 'includes/classes/odoo_product_category.class.php';

// Load libs

// TODO Check dates set from GET params

// TODO Connect to DB
try {
	$_GLOBAL['odooDb'] = new PDO(
		$_GLOBAL['config']['odooDb']['dsn'],
		$_GLOBAL['config']['odooDb']['user'],
		$_GLOBAL['config']['odooDb']['password']
		);
}
catch(PDOException $e) {
	echo 'Connection to OdooDb failed: '.$e->getMessage();
	exit;
}

// Get Categories
$categoriesObj = new odoo_product_categories($_GLOBAL['odooDb']);
$categories = $categoriesObj->categories;

// TODO Get POS sales

// TODO Get customers invoices based on invoice date

// TODO Get suppliers invoices based on invoice date

// TODO Get customers invoices based on payment date

// TODO Get suppliers invoices based on payment date

// TODO Get Analytic for specified categories

// TODO Get debits and credits for each accounting category

// Close DB connection
$_GLOBAL['odooDb'] = NULL;

// TODO Compute the total of each category (adding total of each sub-categories, beginning with the highest leveled categories)

// TODO Generate csv file

// TODO Send csv file
