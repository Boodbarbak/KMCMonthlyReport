<?php
function formatCurrency($amount){
	return number_format($amount, 2);
}

function printReport($categories){
	$sales = new odoo_product_sales();
	$purchases = new odoo_product_sales();
	?>
	<table>
		<tbody>
			<tr>
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
			</tr>
	<?php
	$totalQt = $totalWOTaxes = $totalWTaxes = 0;
	foreach($categories as $cat){
		echo '<tr><td>'.$cat->id.' '.$cat->fullpath.' /</td><td align=right>'.(int)$cat->sales->quantity.'</td><td align=right>'.formatCurrency($cat->sales->totalWOTaxes).'</td><td align=right>'.formatCurrency($cat->sales->totalWTaxes).'</td><td align=right>'.(int)$cat->purchases->quantity.'</td><td align=right>'.formatCurrency($cat->purchases->totalWOTaxes*-1).'</td><td align=right>'.formatCurrency($cat->purchases->totalWTaxes*-1)."</td></tr>\r\n";
		
		$sales->quantity += $cat->sales->quantity;
		$sales->totalWOTaxes += $cat->sales->totalWOTaxes;
		$sales->totalWTaxes += $cat->sales->totalWTaxes;
		
		$purchases->quantity += $cat->purchases->quantity;
		$purchases->totalWOTaxes += $cat->purchases->totalWOTaxes;
		$purchases->totalWTaxes += $cat->purchases->totalWTaxes;
		
		foreach($cat->products as $prod){
			echo '<tr><td>'.$cat->fullpath.' / '.$prod->id.' '.$prod->name.'</td><td align=right>'.(int)$prod->sales->quantity.'</td><td align=right>'.formatCurrency($prod->sales->totalWOTaxes).'</td><td align=right>'.formatCurrency($prod->sales->totalWTaxes).'</td><td align=right>'.(int)$prod->purchases->quantity.'</td><td align=right>'.formatCurrency($prod->purchases->totalWOTaxes*-1).'</td><td align=right>'.formatCurrency($prod->purchases->totalWTaxes*-1)."</td></tr>\r\n";
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
		</tr>
		</tbody>
	</table>
	<?php
}
