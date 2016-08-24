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
$posSales = new odoo_pos_products_sales($GLOBALS['odooDb'], $categoriesObj, $periods, $productsSales);
$productsSales = $posSales->productsSales;
?>
<table>
	<tbody>
		<tr>
			<td>Category</td>
			<td>Quantity</td>
			<td>Total without Taxes</td>
			<td>Total with Taxes</td>
		</tr>
<?php
$totalQt = $totalWOTaxes = $totalWTaxes = 0;
foreach($categories as $cat){
	echo '<tr><td>'.$cat->id.' '.$cat->fullpath.' /</td><td align=right>'.(int)$cat->quantity.'</td><td align=right>'.formatCurrency($cat->totalWOTaxes).'</td><td align=right>'.formatCurrency($cat->totalWTaxes)."</td></tr>\r\n";
	$totalQt += $cat->quantity;
	$totalWOTaxes += $cat->totalWOTaxes;
	$totalWTaxes += $cat->totalWTaxes;
	foreach($cat->productsSales as $prodSales){
		echo '<tr><td>'.$cat->fullpath.' / '.$prodSales->product->id.' '.$prodSales->product->name.'</td><td align=right>'.(int)$prodSales->quantity.'</td><td align=right>'.formatCurrency($prodSales->totalWOTaxes).'</td><td align=right>'.formatCurrency($prodSales->totalWTaxes)."</td></tr>\r\n";
	}
}
?>
	<tr>
		<td>TOTAL</td>
		<td align=right><?=number_format($totalQt) ?></td>
		<td align=right><?=formatCurrency($totalWOTaxes) ?></td>
		<td align=right><?=formatCurrency($totalWTaxes) ?></td>
	</tr>
	</tbody>
</table>
<?php

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

include '_end.php';
