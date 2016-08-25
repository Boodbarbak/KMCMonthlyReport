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
$productsSales = $posSales->productsSales;

// Get customers and suppliers invoices based on invoice period
$invoiceSales = new odoo_invoices_products($GLOBALS['odooDb'], $categoriesObj, $periods, $productsSales);
$productsSales = $invoiceSales->productsSales;

// TODO Get customers and suppliers invoices based on payment period/date

// TODO Get Analytic for specified categories

// TODO Get debits and credits for each accounting category

// Close DB connection
$GLOBALS['odooDb'] = NULL;

// TODO Compute the total of each category (adding total of each sub-categories, beginning with the highest leveled categories)

printReport($categories);

// TODO Generate csv file

// TODO Send csv file

include '_end.php';
