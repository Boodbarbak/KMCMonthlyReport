<?php
// Start the system
include '_start.php';

// TODO Check dates set from GET params

// Get Categories
$categoriesObj = new odoo_product_categories($GLOBALS['odooDb']);
$categories = $categoriesObj->categories;

// TODO Get POS sales

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
