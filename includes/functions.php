<?php
function formatCurrency($amount){
	return number_format($amount, 2);
}

function printReport($categories){
	$sales = new odoo_product_sales();
	$purchases = new odoo_product_sales();
	$salesByPayment = new odoo_product_sales();
	$purchasesByPayment = new odoo_product_sales();
	?>
	<table>
		<tbody>
			<tr>
				<td></td>
				<td colspan=7 align=center style="font-weight:bold;">Based on invoices</td>
				<td colspan=7 align=center style="font-weight:bold;">Based on payments</td>
			</tr>
			<tr>
				<td></td>
				<td colspan=3 align=center style="font-weight:bold;">Incomes</td>
				<td colspan=3 align=center style="font-weight:bold;">Expenditures</td>
				<td></td>
				<td colspan=3 align=center style="font-weight:bold;">Incomes</td>
				<td colspan=3 align=center style="font-weight:bold;">Expenditures</td>
			</tr>
			<tr>
				<td>Category</td>

				<td>Quantity</td>
				<td>Total without Taxes</td>
				<td>Total with Taxes</td>

				<td>Quantity</td>
				<td>Total without Taxes</td>
				<td>Total with Taxes</td>
				
				<td>Balance</td>

				<td>Quantity</td>
				<td>Total without Taxes</td>
				<td>Total with Taxes</td>

				<td>Quantity</td>
				<td>Total without Taxes</td>
				<td>Total with Taxes</td>
				
				<td>Balance</td>
			</tr>
	<?php
	$totalQt = $totalWOTaxes = $totalWTaxes = 0;
	foreach($categories as $cat){
		echo '<tr>';
		echo '<td title="'.$cat->id.'">'.$cat->fullpath.' /</td>';
		printSalesNPurchasesCells($cat);
		echo "</tr>\r\n";
		
		if($cat->level == 0){
			$sales->quantity += $cat->sales->quantity;
			$sales->totalWOTaxes += $cat->sales->totalWOTaxes;
			$sales->totalWTaxes += $cat->sales->totalWTaxes;
		
			$purchases->quantity += $cat->purchases->quantity;
			$purchases->totalWOTaxes += $cat->purchases->totalWOTaxes;
			$purchases->totalWTaxes += $cat->purchases->totalWTaxes;
		
			$salesByPayment->quantity += $cat->salesByPayment->quantity;
			$salesByPayment->totalWOTaxes += $cat->salesByPayment->totalWOTaxes;
			$salesByPayment->totalWTaxes += $cat->salesByPayment->totalWTaxes;
		
			$purchasesByPayment->quantity += $cat->purchasesByPayment->quantity;
			$purchasesByPayment->totalWOTaxes += $cat->purchasesByPayment->totalWOTaxes;
			$purchasesByPayment->totalWTaxes += $cat->purchasesByPayment->totalWTaxes;
		}
		
		if(isset($cat->products)){
			foreach($cat->products as $prod){
				echo '<tr>';
				echo '<td title="'.$cat->id.' / '.$prod->id.'">(...) / '.' '.$prod->name.'</td>';
				printSalesNPurchasesCells($prod);
				echo "</tr>\r\n";
			}
		}
	}
	?>
		<tr>
			<td>TOTAL</td>
			
			<td align=right><?=number_format($sales->quantity) ?></td>
			<td align=right><?=formatCurrency($sales->totalWOTaxes) ?></td>
			<td align=right><?=formatCurrency($sales->totalWTaxes) ?></td>
			
			<td align=right><?=number_format($purchases->quantity) ?></td>
			<td align=right><?=formatCurrency($purchases->totalWOTaxes*-1) ?></td>
			<td align=right><?=formatCurrency($purchases->totalWTaxes*-1) ?></td>
			<td align=right><?=formatCurrency($sales->totalWOTaxes-$purchases->totalWOTaxes) ?></td>
			
			<td align=right><?=number_format($salesByPayment->quantity) ?></td>
			<td align=right><?=formatCurrency($salesByPayment->totalWOTaxes) ?></td>
			<td align=right><?=formatCurrency($salesByPayment->totalWTaxes) ?></td>
			
			<td align=right><?=number_format($purchasesByPayment->quantity) ?></td>
			<td align=right><?=formatCurrency($purchasesByPayment->totalWOTaxes*-1) ?></td>
			<td align=right><?=formatCurrency($purchasesByPayment->totalWTaxes*-1) ?></td>
			<td align=right><?=formatCurrency($salesByPayment->totalWOTaxes-$purchasesByPayment->totalWOTaxes) ?></td>
		</tr>
		</tbody>
	</table>
	<?php
}

function printSalesNPurchasesCells($obj){
	printSalesCells($obj->sales);
	printPurchasesCells($obj->purchases);
	echo '<td align=right>'.formatCurrency($obj->sales->totalWOTaxes-$obj->purchases->totalWOTaxes).'</td>';

	printSalesCells($obj->salesByPayment);
	printPurchasesCells($obj->purchasesByPayment);
	echo '<td align=right>'.formatCurrency($obj->salesByPayment->totalWOTaxes-$obj->purchasesByPayment->totalWOTaxes).'</td>';
}

function printSalesCells($obj){
		echo '<td align=right>'.number_format($obj->quantity,2).'</td><td align=right>'.formatCurrency($obj->totalWOTaxes).'</td><td align=right>'.formatCurrency($obj->totalWTaxes).'</td>';
}

function printPurchasesCells($obj){
		echo '<td align=right>'.number_format($obj->quantity,2).'</td><td align=right>'.formatCurrency($obj->totalWOTaxes*-1).'</td><td align=right>'.formatCurrency($obj->totalWTaxes*-1).'</td>';
}
