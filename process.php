<?php
// Start the system
include '_start.php';

// TODO Check period and dates set from GET params
// TODO Select corresponding period for all companies
$periods = array($_GET['period']);

// Get Categories
$categoriesObj = new odoo_product_categories($GLOBALS['odooDb']);
$categories = $categoriesObj->categories;

$productsSales = array();

// Get POS sales
$posSales = new odoo_pos_products_sales($GLOBALS['odooDb'], $categoriesObj, $periods, $productsSales);
$productsSales = $posSales->products;

// Get customers and suppliers invoices based on invoice period
$invoiceSales = new odoo_invoices_products($GLOBALS['odooDb'], $categoriesObj, $periods, $productsSales);
$productsSales = $invoiceSales->products;

// Get customers and suppliers invoices based on payment period
$invoiceSalesByPeriod = new odoo_invoices_products($GLOBALS['odooDb'], $categoriesObj, $periods, $productsSales, true);
$productsSales = $invoiceSales->products;


// TODO Get Analytic for specified categories

// Get debits and credits for each accounting category
$categoriesByAccount = array();
// Adding the accounting categories to the categories list
foreach($GLOBALS['config']['odoo']['accountCategories'] as $data){
	$cat = new odoo_account_category($categories, $categoriesByAccount, $data);
	$categories = $cat->categories;
	$categoriesByAccount = $cat->categoriesByAccount;
}
// Fetching the total amount for each account
$accountsFetcher = new odoo_account_account($GLOBALS['odooDb']);
$accountsTotals = $accountsFetcher->getAccountsTotal(array_keys($categoriesByAccount), $periods);
// Adding the total amount to the appropriate category
foreach($accountsTotals as $account=>$data){
	$categoriesByAccount[$account]->add($data['quantity'], $data['total']);
}

// Close DB connection
$GLOBALS['odooDb'] = NULL;

// TODO Compute the total of each category (adding total of each sub-categories, beginning with the highest leveled categories)

printReport($categories);

// TODO Generate csv file

// TODO Send csv file

include '_end.php';
