<?php
require_once('settings.php');

$authentication->authenticate();

$export['nationals']['sql'] = 'SHOW FULL COLUMNS FROM nationals';
$export['passports']['sql'] = 'SHOW FULL COLUMNS FROM passports';
$export['restorations']['sql'] = 'SHOW FULL COLUMNS FROM restorations';
$export['birth_certificates']['sql'] = 'SHOW FULL COLUMNS FROM birth_certificates';
if (
	$export['nationals']['query'] = mysql_query($export['nationals']['sql']) and mysql_num_rows($export['nationals']['query']) > 0 and
	$export['passports']['query'] = mysql_query($export['passports']['sql']) and mysql_num_rows($export['passports']['query']) > 0 and
	$export['restorations']['query'] = mysql_query($export['restorations']['sql']) and mysql_num_rows($export['restorations']['query']) > 0 and
	$export['birth_certificates']['query'] = mysql_query($export['birth_certificates']['sql']) and mysql_num_rows($export['birth_certificates']['query']) > 0
) {

	/* Export the database */
	if ($_POST) {
		
		/* Get pretty column names from column comments */
		foreach ($export as $table_name => $table_data) {
			while ($table_data['results'] = mysql_fetch_assoc($table_data['query'])) {
				$column_info[$table_name][$table_data['results']['Field']]['name'] = $table_data['results']['Comment'];
				$column_info[$table_name][$table_data['results']['Field']]['type'] = $table_data['results']['Type'];
			}
		}

		/* Extract date limits */
		$date_limit = $_POST['date_limit'];
		unset($_POST['date_limit']);

		/* Build date limit syntax for SELECT query */
		foreach ($date_limit as $table => $fields) {
			foreach ($fields as $field => $value) {
				if (!empty($value)) {
					$date_limit_sql[] = "{$table}.{$field} = '{$value}'";
				}
			}
		}
		if (is_array($date_limit_sql)) {
			$date_limit_sql = 'WHERE '.implode(' AND ', $date_limit_sql);
		}

//		print '<pre>';
//		print_r($column_info);
//		print_r($date_limit);
//		print $date_limit_sql;
//		exit;

		/* Build column syntax for SELECT query and column headers */
		$export['nationals']['title'] = '';
		$export['passports']['title'] = 'Passport ';
		$export['restorations']['title'] = 'Restoration ';
		$export['birth_certificates']['title'] = 'Birth certificate ';
		foreach ($_POST as $table => $fields) {
			foreach ($fields as $field => $value) {
				$columns_sql[] = "{$table}.{$field} AS '{$export[$table]['title']}{$column_info[$table][$field]['name']}'";
				$date_limit_header = ( (empty($date_limit[$table][$field])) ? '' : " is {$date_limit[$table][$field]}" );
				$column_info['columns_in_use'][] = ucfirst(strtolower($export[$table]['title'].$column_info[$table][$field]['name'].$date_limit_header));
			}
		}
		$columns_sql = implode(', ', $columns_sql);
		
		/* Query the database */
		$export_for_excel['sql'] = "	SELECT	nationals.id, {$columns_sql}
								FROM		nationals
								LEFT JOIN	passports			ON nationals.id = passports.id
								LEFT JOIN	restorations		ON nationals.id = restorations.id
								LEFT JOIN	birth_certificates	ON nationals.id = birth_certificates.id
								{$date_limit_sql}";
		if ($export_for_excel['query'] = mysql_query($export_for_excel['sql']) and mysql_num_rows($export_for_excel['query']) > 0) {
			header('Content-Type: application/vnd.ms-excel'); 
			header('Content-Disposition: attachment; filename="Nationals Database exported by '.$_SESSION['name_first'][0].' '.$_SESSION['name_last'].' ('.date('Y-m-d H-i-s').').xls"');
			header('Pragma: no-cache');
			header('Expires: 0');
?>
<table style="border-collapse:collapse; empty-cells:show; font:12px/1.5 helvetica, arial, sans-serif; letter-spacing:-1px;">
	<thead>
		<tr>
			<th style="padding:2px 4px; background:#F0F0F0; border-bottom:2px solid #555">ID</th>
<?php 
			foreach ($column_info['columns_in_use'] as $column_header) {
?>
			<th style="padding:2px 4px; background:#F0F0F0; border-bottom:2px solid #555"><?php print $column_header ?></th>
<?php
			}
?>
		</tr>
	</thead>
<?php
			while ($export_for_excel['results'] = mysql_fetch_assoc($export_for_excel['query'])) {
?>
	<tbody>
		<tr>
<?php
				foreach ($export_for_excel['results'] as $column => $value) {
					if ($column == 'Province') {
						$value = $provinces_territories[$value][0];
					}
					if (preg_match(REG_EX_DATE, $value)) {
						$value = date('d-M-Y', strtotime($value));
					}
					if ($value == '0000-00-00') {
						$value = '';
					}
					if (strpos($column, 'modified by')) {
						if ($value != 0) {
							$value = mysql_result(mysql_query("SELECT concat(name_first, ' ', name_last) FROM users WHERE id = {$value}"), 0);
						} else {
							$value = 'No one';
						}
					}
?>
			<td style="padding:2px 4px; border:1px solid #F0F0F0;"><?php print $value ?></td>
<?php
				}
?>
		</tr>
	</tbody>
<?php
			}
?>
</table>
<?php
			exit;
		} elseif (mysql_num_rows($export_for_excel['query']) == 0) {
			$error = 'No records founds';
		} else {
			die(mysql_error());
		}
	}

	/* Allow user to select database columns of interest */
	$layout->print_header();
?>
<h2>Export</h2>
<?php
	$export['nationals']['title'] = 'Contact details';
	$export['passports']['title'] = 'Passport details';
	$export['restorations']['title'] = 'Restoration details';
	$export['birth_certificates']['title'] = 'Birth certificate details';
?>
<script type="text/javascript">
//<![CDATA[
$(function(){
	$('.date_picker').datepicker( {
		dateFormat	: 'yy-mm-dd',
		changeMonth	: true,
		changeYear	: true
	});

	$('form').submit(
		function() {
			if ( $('input:checked').length ) {
				$(':submit').removeAttr('disabled').attr('value','Export');
				return true;
			} else {
				$.prompt('At least one field must be exported', { buttons: { 'Try again': true }, callback: function(){ $(':submit').removeAttr('disabled').attr('value','Export'); } });
				return false;
			}
		}
	);
	$(':checkbox').each( function() {
		$(this).change( function() {
			if (this.checked == true) {
				$('#'+this.id+'_limit').show('normal');
			} else {
				$('#'+this.id+'_limit').hide('normal');
			}
		})
	});
<?php
	if ($error) {
?>
	$.prompt('No nationals matching that criteria were found in the database.', { buttons: { 'Try again': true } });
<?php
	}
?>
});
//]]>
</script>
<form method="post" action="<?php print $_SERVER['PHP_SELF'] ?>">
	<fieldset>
<?php
	foreach ($export as $table_name => $table_data) {
?>
		<fieldset class="export_column">
<?php
		if ($table_data['title']) {
?>
			<h3><?php print $table_data['title'] ?></h3>
<?php
		}
?>
			<ul>
<?php
		mysql_data_seek($table_data['query'],0);
		while ($table_data['results'] = mysql_fetch_assoc($table_data['query'])) {
			if (!empty($table_data['results']['Comment'])) {
?>
				<li>
					<input type="checkbox" name="<?php print "{$table_name}[{$table_data['results']['Field']}]" ?>" id="<?php print "{$table_name}_{$table_data['results']['Field']}" ?>" /><label for="<?php print "{$table_name}_{$table_data['results']['Field']}" ?>"><?php print $table_data['results']['Comment'] ?></label>
<?php if ($table_data['results']['Type'] == 'date') { ?>
					<div id="<?php print "{$table_name}_{$table_data['results']['Field']}" ?>_limit" class="no-display">
						is <input title="Limit <?php print strtolower($table_data['results']['Comment']) ?>" name="<?php print "date_limit[{$table_name}][{$table_data['results']['Field']}]" ?>" type="text" class="date_picker" style="width:56px;" maxlength="10" />
					</div>
					
<?php } ?>
				</li>
<?php
			}
		}

?>
			</ul>
		</fieldset>
<?php
	}
?>
		<fieldset class="controls">
			<input type="button" value="Back" onclick="history.back()" />
			<input type="reset" value="Reset" />
			<input type="submit" value="Export" />
		</fieldset>
	</fieldset>
</form>
<?php
	$layout->print_footer();
} else {
	die(mysql_error());
}
?>