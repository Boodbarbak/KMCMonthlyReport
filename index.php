<?php
// Start the system
include '_start.php';
// Get the periods
$periods = new odoo_account_periods($GLOBALS['odooDb'], $GLOBALS['config']['events']);
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
	<script src="includes/jquery/datepicker-fr.js" type="text/javascript"></script>
	<script>
	<?php
	if(count($GLOBALS['config']['events']))
	{
		echo "var specialEventsDates = new Array();\r\n";
		foreach($periods->periodsByCompany[$GLOBALS['config']['odoo']['companies'][0]] as $index=>$period){
			if(isset($GLOBALS['config']['events'][$period->name])){
				$dates = $GLOBALS['config']['events'][$period->name];
				$parts = explode('-',$dates[0]);
				$startDate = $parts[2].'/'.$parts[1].'/'.$parts[0];
				$parts = explode('-',$dates[1]);
				$endDate = $parts[2].'/'.$parts[1].'/'.$parts[0];
			}
			else{
				$startDate = $endDate = '';
			}
			echo 'specialEventsDates['.$index.'] = {start: "'.$startDate.'", end: "'.$endDate.'"};'."\r\n";
		}
	}
	?>
	$(function() {
		var datePickerConf = {
			changeMonth: true,
			changeYear: true
		};
		$.datepicker.setDefaults( $.datepicker.regional[ "fr" ] );
		$("#idate").datepicker(datePickerConf);
		$("#odate").datepicker(datePickerConf);
		$("#periodSelector").change(function(){
			if(specialEventsDates[this.value] == undefined){
				$("#idate").val('');
				$("#odate").val('');
			}
			else{
				$("#idate").val(specialEventsDates[this.value].start);
				$("#odate").val(specialEventsDates[this.value].end);
			}
		})
	});
	</script>
</head>
<body>
	<div>
		<h1><span style="font-weight:bold;">Générateur de Rapport Financié Mensuel</span></h1>
		<p>
		</p>
		<form target="_self" autocomplete="off" method="GET" action="process.php">
			Choisir au moins une entitée :<br>
			<input type=checkbox name=companies[] value=1 checked> CMK France / Editions Tharpa<br>
			<input type=checkbox name=companies[] value=3 checked> IRCB<br><br>

			<select name="period" id="periodSelector">
				<option value="">Choisir une période</option>
				<?php
				foreach($periods->periodsByCompany[$GLOBALS['config']['odoo']['companies'][0]] as $index=>$period){
					echo '<option value="'.$index.'">'.htmlspecialchars($period->name).'</option>'."\n";
				}
				?>
			</select><br><br>

			Évenement spécial sur la période : du <input maxlength="12" size="10" value="" name="eventStart" id="idate" type="text">(inclus) au <input maxlength="12" size="10" value="" name="eventEnd" id="odate" type="text">(inclus)<br><br>
			<input type="submit" value="Générer le rapport">
		</form>
	</div>
	<span></span>
</body>
</html><?php
include '_end.php';
?>
