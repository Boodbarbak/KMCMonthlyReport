<?php
// Goto to index if no period
if(!isset($_GET['period']) || !(int)$_GET['period'] || !isset($_GET['companies']) || !is_array($_GET['companies'])){
	header('Location: ./');
	exit;
}

// Start the system
include '_start.php';
?>
<html>
<head>
	<meta content="text/html; charset=UTF-8" http-equiv="content-type">
	<title>CMK - Générateur de Rapport Financié Mensuel</title>
	<script src="includes/jquery/jquery-2.1.3.min.js" type="text/javascript"></script>
	<script src="includes/jquery/jquery.floatThead.min.js" type="text/javascript"></script>
	<script>
	$(function() {
		$('table.datatable').floatThead();
			});
	</script>
	<style type=text/css>
	body, td{
		font-family: "sans-serif";
		font-size: 10pt;
	}
	table{
		border-spacing: 0;
	}
	table thead{
		background-color: white;
	}
	table td{
		border: 1px solid grey;
		border-top:0;
		border-left:0;
	}
	</style>
</head>
<body>
<?php
// Selecting the corresponding period of each companies
$periodsFetcher = new odoo_account_periods($GLOBALS['odooDb']);
$periods = array();
foreach($_GET['companies'] as $companyId){
	$periods[] = $periodsFetcher->periodsByCompany[$companyId][$_GET['period']]->id;
}

// *** Get Categories ***
$categoriesObj = new odoo_product_categories($GLOBALS['odooDb']);
$categories = $categoriesObj->categories;
// *** /Get Categories ***

$productsSales = array();

// *** POS sales ***
$posSales = new odoo_pos_products_sales($GLOBALS['odooDb'], $categoriesObj, $periods, $productsSales);
$productsSales = $posSales->products;
// *** /POS sales ***

// *** Special events ***
// If there is a special event in this period
if(isset($_GET['eventStart']) && isset($_GET['eventEnd']) && strlen($_GET['eventStart'])==10 &&  strlen($_GET['eventEnd'])==10){
	// Get POS sales of the shop for the period excluding the event
	// Replace the categories sales by those sales
	// Get POS total sales of the shop for the period only for the event
	// Place it in a new category Events / Shop
}
// *** /Special events ***

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

// Compute the total of each category (adding total of each sub-categories to its parent category, beginning with the highest leveled categories)
for($i=count($categoriesObj->categoriesByLevel)-1; $i>0; $i--){
	foreach($categoriesObj->categoriesByLevel[$i] as $cat){
		$cat->parent->add($cat);
	}
}

// Show report
printReport($periodsFetcher->periods[$_GET['period']]->name, $categories);

// TODO Generate csv file

// TODO Send csv file

include '_end.php';
?>
</body>
</html>
