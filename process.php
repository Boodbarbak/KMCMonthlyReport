<?php
// Start the system
include '_start.php';

// TODO Check period and dates set from GET params
$periods = array($_GET['period']);

// Get Categories
$categoriesObj = new odoo_product_categories($GLOBALS['odooDb']);
$categories = $categoriesObj->categories;

$productsSales = array();

// TODO Get POS sales
$posSales = new odoo_pos_products_sales($GLOBALS['odooDb'], $periods, $productsSales);
$productsSales = $posSales->productsSales;
var_dump($productsSales);

// TODO Get customers invoices based on invoice date

// TODO Get suppliers invoices based on invoice date

// TODO Get customers invoices based on payment date

// TODO Get suppliers invoices based on payment date

// TODO Get Analytic for specified categories

// TODO Get debits and credits for each accounting category

// Close DB connection
$GLOBALS['odooDb'] = NULL;

// TODO Compute the total of each category (adding total of each sub-categories, beginning with the highest leveled categories)

// TODO Generate csv file

// TODO Send csv file
