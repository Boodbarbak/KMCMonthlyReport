<?php
// Start the system
include '_start.php';
// Get the periods
$periods = new odoo_account_periods($GLOBALS['odooDb']);
?>
<html>
<head>
	<meta content="text/html; charset=UTF-8" http-equiv="content-type">
	<title>CMK - Générateur de Rapport Financié Mensuel</title>
	<link href="includes/jquery/jquery-ui.min.css" rel="stylesheet" type="text/css" />
	<link href="includes/jquery/jquery-ui.structure.min.css" rel="stylesheet" type="text/css" />
	<link href="includes/jquery/jquery-ui.theme.min.css" rel="stylesheet" type="text/css" />
	<script src="includes/jquery/jquery-2.1.3.min.js" type="text/javascript"></script>
	<script src="includes/jquery/jquery-ui.min.js" type="text/javascript"></script>
	<script src="includes/jquery/jquery-ui.interactions.min.js" type="text/javascript"></script>
	<script>
	$(function() {
			$( "#idate" ).datepicker();
			$( "#odate" ).datepicker();
			});
	</script>
</head>
<body>
	<div>
		<h1><span style="font-weight:bold;">Générateur de Rapport Financié Mensuel</span></h1>
		<p>
		</p>
		<form target="_self" autocomplete="off" method="GET" action="process.php">
			<select name="period">
				<option value="">Choisir une période</option>
				<?php
				foreach($periods->periodsByCompany[$GLOBALS['config']['odoo']['companies'][0]] as $period){
					echo '<option value="'.$period->id.'">'.htmlspecialchars($period->name).'</option>'."\n";
				}
				?>
			</select>
			<!--table>
				<tbody>
					<tr>
						<td>Date début</td>
						<td style="height: 25px;"><input maxlength="12" size="10" value="" name="idate" id="idate" type="text"><br>
						</td>
					</tr>
					<tr>
						<td>Date de fin</td>
						<td> <input maxlength="12" size="10" value="" name="odate" id="odate" type="text"></td>
					</tr>
				</tbody>
			</table-->
			<input name="send" type="submit" value="Générer le rapport">
		</form>
	</div>
	<span></span>
</body>
</html><?php
include '_end.php';
?>
