<?php
// Start the system
include '_start.php';

// TODO Check period and dates set from GET params
// TODO Select corresponding period for all companies
$periods = array($_GET['period']);

// *** Get Categories ***
$categoriesObj = new odoo_product_categories($GLOBALS['odooDb']);
$categories = $categoriesObj->categories;
// *** /Get Categories ***

$productsSales = array();

// *** POS sales ***
$posSales = new odoo_pos_products_sales($GLOBALS['odooDb'], $categoriesObj, $periods, $productsSales);
$productsSales = $posSales->products;
// *** /POS sales ***

// *** Customers and suppliers invoices based on invoice period ***
$invoiceSales = new odoo_invoices_products($GLOBALS['odooDb'], $categoriesObj, $periods, $productsSales);
$productsSales = $invoiceSales->products;
// *** /Customers and suppliers invoices based on invoice period ***

// *** Customers and suppliers invoices based on payment period ***
$invoiceSalesByPeriod = new odoo_invoices_products($GLOBALS['odooDb'], $categoriesObj, $periods, $productsSales, true);
$productsSales = $invoiceSales->products;
// *** /Customers and suppliers invoices based on payment period ***

// TODO Get Analytic for specified categories

// *** Accounting categories ***
$categoriesByAccount = array();
$categoriesToMerge = array();

// Adding the accounting categories to the categories list
foreach($GLOBALS['config']['odoo']['accountCategories'] as $data){
	$cat = new odoo_account_category($categories, $categoriesByAccount, $categoriesObj->categoriesById, $categoriesObj->categoriesByLevel, $data);
	$categories = $cat->categories;
	$categoriesByAccount = $cat->categoriesByAccount;
	$categoriesObj->categoriesByLevel = $cat->categoriesByLevel;
	if($cat->id)
		$categoriesToMerge[] = $cat;
}

// Fetching the total amount for each account
$accountsFetcher = new odoo_account_account($GLOBALS['odooDb']);
$accountsTotals = $accountsFetcher->getAccountsTotal(array_keys($categoriesByAccount), $periods);

// Adding the total amount to the appropriate category
foreach($accountsTotals as $account=>$data){
	$categoriesByAccount[$account]->add($data);
}

// Merging accounting categories with products categories, if appropriate
foreach($categoriesToMerge as $cat){
	// Adding the totals of the accounting category to the products category
	$categoriesObj->categoriesById[$cat->id]->add($cat);
}
// *** /Accounting categories ***

// Close DB connection
$GLOBALS['odooDb'] = NULL;

// TODO Compute the total of each category (adding total of each sub-categories to its parent category, beginning with the highest leveled categories)
for($i=count($categoriesObj->categoriesByLevel)-1; $i>0; $i--){
	foreach($categoriesObj->categoriesByLevel[$i] as $cat){
		$cat->parent->add($cat);
	}
}

printReport($categories);

// TODO Generate csv file

// TODO Send csv file

include '_end.php';
