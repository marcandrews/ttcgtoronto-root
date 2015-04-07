<?php
require_once('settings.php');

$authentication->authenticate();

/* Function to check whether an array has values */
function has_values ($array) {
	foreach ($array as $value) {
		if (!empty($value)) {
			return true;
		}
	}
	return false;
}

if (isset($_GET['add']) or isset($_GET['edit'])) {

	/* Modifications will be made to the database */
	if ($_POST) {
		$output['result']	= true;
		$date_error_message	= 'A date (<abbr title="example given">e.g.</abbr> '.date("Y-m-d").') is required.';
	
		if ($_SESSION['permission_nat'] > 0) {
		
			/* Validate first name */
			if (!preg_match('/\w+/', $_POST['name_first'])) {
				$output['result'] = false;
				$output['error']['validation']['name_first'] = 'A first name is required.';
			}
		
			/* Validate last name */
			if (!preg_match('/\w+/', $_POST['name_last'])) {
				$output['result'] = false;
				$output['error']['validation']['name_last'] = 'A last name is required.';
			}
		
			/* Validate gender */
			if (!preg_match('/[FM]/', $_POST['gender'])) {
				$output['result'] = false;
				$output['error']['validation']['gender'] = 'Gender is required.';
			}
		
			/* Validate address line 1 */
			if (!preg_match('/\w+/', $_POST['address_1'])) {
				$output['result'] = false;
				$output['error']['validation']['address_1'] = 'An address is required.';
			}
		
			/* Validate city */
			if (!preg_match('/\w+/', $_POST['city'])) {
				$output['result'] = false;
				$output['error']['validation']['city'] = 'A city is required.';
			}
		
			/* Validate postal code */
			if (preg_match(REG_EX_POSTAL_CODE, $_POST['postal_code'])) {
				$_POST['postal_code'] = strtoupper($_POST['postal_code']);
			} else {
				$output['result'] = false;
				$output['error']['validation']['postal_code'] = 'A valid Canadian postal code (<abbr title="example given">e.g.</abbr> A9A 9A9) is required.';
			}
		
			/* Validate telephone number */
			if (!preg_match(REG_EX_TEL, $_POST['tel'])) {
				$output['result'] = false;
				$output['error']['validation']['tel'] = 'A valid telephone number (<abbr title="example given">e.g.</abbr> (999) 999-9999)  is required.';
			}

			/* Validate telephone secondary number */
			if (!empty($_POST['tel_2']) and !preg_match(REG_EX_TEL, $_POST['tel_2'])) {
				$output['result'] = false;
				$output['error']['validation']['tel_2'] = 'A valid telephone number (<abbr title="example given">e.g.</abbr> (999) 999-9999)  is required.';
			}

			/* Validate email */
			if (!empty($_POST['email']) and !preg_match(REG_EX_EMAIL, $_POST['email'])) {
				$output['result'] = false;
				$output['error']['validation']['email'] = 'A valid email address is required.';
			}
			/* Validate validity end date */
			if (!empty($_POST['validity_end_date']) and !preg_match(REG_EX_DATE, $_POST['validity_end_date'])) {
				$output['result'] = false;
				$output['error']['validation']['validity_end_date'] = $date_error_message;
			}
		}
		if ($_SESSION['permission_pp'] > 0) {
			
			/* Validate passport date received */
			if (!empty($_POST['pp']['date_received']) and !preg_match(REG_EX_DATE, $_POST['pp']['date_received'])) {
				$output['result'] = false;
				$output['error']['validation']['pp']['date_received'] = $date_error_message;
			}
		
			/* Validate passport date sent to POS */
			if (!empty($_POST['pp']['date_sent_to_pos']) and !preg_match(REG_EX_DATE, $_POST['pp']['date_sent_to_pos'])) {
				$output['result'] = false;
				$output['error']['validation']['pp']['date_sent_to_pos'] = $date_error_message;
			}
		
			/* Validate passport date received from POS */
			if (!empty($_POST['pp']['date_received_from_pos']) and !preg_match(REG_EX_DATE, $_POST['pp']['date_received_from_pos'])) {
				$output['result'] = false;
				$output['error']['validation']['pp']['date_received_from_pos'] = $date_error_message;
			}
		
			/* Validate passport date activated */
			if (!empty($_POST['pp']['date_activated']) and !preg_match(REG_EX_DATE, $_POST['pp']['date_activated'])) {
				$output['result'] = false;
				$output['error']['validation']['pp']['date_activated'] = $date_error_message;
			}
		
			/* Validate passport number */
			if (!empty($_POST['pp']['pp_number'])) {
				if (preg_match('/^\D{2}\d{6,7}$/', $_POST['pp']['pp_number'])) {
					$_POST['pp']['pp_number'] = strtoupper($_POST['pp']['pp_number']);
				} else {
					$output['result'] = false;
					$output['error']['validation']['pp']['pp_number'] = 'A valid passport number (<abbr title="example given">e.g.</abbr> AA999999) is required.';
				}
			}
		
			/* Validate passport date sent to applicant */
			if (!empty($_POST['pp']['date_sent_to_applicant']) and !preg_match(REG_EX_DATE, $_POST['pp']['date_sent_to_applicant'])) {
				$output['result'] = false;
				$output['error']['validation']['pp']['date_sent_to_applicant'] = $date_error_message;
			}
		}
		if ($_SESSION['permission_rest'] > 0) {
			/* Validate restoration date received */
			if (!empty($_POST['r']['date_received']) and !preg_match(REG_EX_DATE, $_POST['r']['date_received'])) {
				$output['result'] = false;
				$output['error']['validation']['r']['date_received'] = $date_error_message;
			}
		
			/* Validate restoration date sent to POS */
			if (!empty($_POST['r']['date_sent_to_pos']) and !preg_match(REG_EX_DATE, $_POST['r']['date_sent_to_pos'])) {
				$output['result'] = false;
				$output['error']['validation']['r']['date_sent_to_pos'] = $date_error_message;
			}
		
			/* Validate restoration date received from POS */
			if (!empty($_POST['r']['date_received_from_pos']) and !preg_match(REG_EX_DATE, $_POST['r']['date_received_from_pos'])) {
				$output['result'] = false;
				$output['error']['validation']['r']['date_received_from_pos'] = $date_error_message;
			}
		
			/* Validate restoration cert. issued */
			if (!empty($_POST['r']['date_rest_cert_issued']) and !preg_match(REG_EX_DATE, $_POST['r']['date_rest_cert_issued'])) {
				$output['result'] = false;
				$output['error']['validation']['r']['date_rest_cert_issued'] = $date_error_message;
			}
		
			/* Validate restoration number */
			if (!empty($_POST['r']['rest_cert_number']) and !preg_match('/^\D?\d{4}|\d{4}$/', $_POST['r']['rest_cert_number'])) {
				$output['result'] = false;
				$output['error']['validation']['r']['rest_cert_number'] = 'A valid restoration number (<abbr title="example given">e.g.</abbr> A9999 or 9999) is required.';
			}
		
			/* Validate restoration date sent to applicant */
			if (!empty($_POST['r']['date_sent_to_applicant']) and !preg_match(REG_EX_DATE, $_POST['r']['date_sent_to_applicant'])) {
				$output['result'] = false;
				$output['error']['validation']['r']['date_sent_to_applicant'] = $date_error_message;
			}
		}
		if ($_SESSION['permission_birth_cert'] > 0) {
			/* Validate birth certificate date received */
			if (!empty($_POST['bc']['date_received']) and !preg_match(REG_EX_DATE, $_POST['bc']['date_received'])) {
				$output['result'] = false;
				$output['error']['validation']['bc']['date_received'] = $date_error_message;
			}
		
			/* Validate birth certificate date sent to POS */
			if (!empty($_POST['bc']['date_sent_to_pos']) and !preg_match(REG_EX_DATE, $_POST['bc']['date_sent_to_pos'])) {
				$output['result'] = false;
				$output['error']['validation']['bc']['date_sent_to_pos'] = $date_error_message;
			}
		
			/* Validate birth certificate date received from POS */
			if (!empty($_POST['bc']['date_received_from_pos']) and !preg_match(REG_EX_DATE, $_POST['bc']['date_received_from_pos'])) {
				$output['result'] = false;
				$output['error']['validation']['bc']['date_received_from_pos'] = $date_error_message;
			}
		
			/* Validate birth certificate date activated */
			if (!empty($_POST['bc']['date_activated']) and !preg_match(REG_EX_DATE, $_POST['bc']['date_activated'])) {
				$output['result'] = false;
				$output['error']['validation']['bc']['date_activated'] = $date_error_message;
			}
		
			/* Validate birth certificate pin number */
			if (!empty($_POST['bc']['pin_number']) and !preg_match('/^\d{10}$/', $_POST['bc']['pin_number'])) {
				$output['result'] = false;
				$output['error']['validation']['bc']['pin_number'] = 'A valid birth certificate pin number (<abbr title="example given">e.g.</abbr> 999999999) is required.';
			}
		
			/* Validate birth certificate number */
			if (!empty($_POST['bc']['birth_cert_number'])) {
				if (preg_match('/^\D{1}\d{6,7}$/', $_POST['bc']['birth_cert_number'])) {
					$_POST['bc']['birth_cert_number'] = strtoupper($_POST['bc']['birth_cert_number']);
				} else {
					$output['result'] = false;
					$output['error']['validation']['bc']['birth_cert_number'] = 'A valid birth certificate number (<abbr title="example given">e.g.</abbr> A999999 or A9999999) is required.';
				}
			}
		
			/* Validate birth certificate date sent to applicant */
			if (!empty($_POST['bc']['date_sent_to_applicant']) and !preg_match(REG_EX_DATE, $_POST['bc']['date_sent_to_applicant'])) {
				$output['result'] = false;
				$output['error']['validation']['bc']['date_sent_to_applicant'] = $date_error_message;
			}

			/* Validate birth certificate date of birth applicant */
			if (!empty($_POST['bc']['date_of_birth']) and !preg_match(REG_EX_DATE, $_POST['bc']['date_of_birth'])) {
				$output['result'] = false;
				$output['error']['validation']['bc']['date_of_birth'] = $date_error_message;
			}
		}
		if (!$output['result']) {
			print json_encode($output);
			exit;
		}
	
		/* A national is being added to the database */
		if (isset($_GET['add'])) {
			
			$nat['handler'] = 'INSERT INTO';
			$nat['id'] = '';
			$nat['where'] = '';
			$pp_r_bc['handler'] = 'INSERT INTO';
			$pp_r_bc['id'] = "id = {mysql_insert_id()},";
			$syntax['modified'] = "modified = 0,";
			$syntax['created'] = "created = CURRENT_TIMESTAMP";
			$syntax['pp']['modified'] = "modified = " . ( ($_POST['pp']['created']) ? "CURRENT_TIMESTAMP," : "0," );
			$syntax['pp']['created'] = "created = " . ( ($_POST['pp']['created']) ? "'{$_POST['pp']['created']}'" : "CURRENT_TIMESTAMP" );
			$syntax['r']['modified'] = "modified = " . ( ($_POST['r']['created']) ? "CURRENT_TIMESTAMP," : "0," );
			$syntax['r']['created'] = "created = " . ( ($_POST['r']['created']) ? "'{$_POST['r']['created']}'" : "CURRENT_TIMESTAMP" );
			$syntax['bc']['modified'] = "modified = " . ( ($_POST['bc']['created']) ? "CURRENT_TIMESTAMP," : "0," );
			$syntax['bc']['created'] = "created = " . ( ($_POST['bc']['created']) ? "'{$_POST['bc']['created']}'" : "CURRENT_TIMESTAMP" );
	
		/* An existing national is being edited */
		} elseif (isset($_GET['edit'])) {
			$_GET['edit'] = (int) $_GET['edit'];
			$nat['sql'] = "SELECT * FROM nationals WHERE id = {$_GET['edit']} LIMIT 1";
			if ($nat['query'] = mysql_query($nat['sql']) and mysql_num_rows($nat['query']) == 1) { // National exists
				$nat['handler'] = 'UPDATE';
				$nat['id'] = "id = {$_GET['edit']},";
				$nat['where'] = "WHERE id = {$_GET['edit']} LIMIT 1";
				$pp_r_bc['handler'] = 'REPLACE';
				$pp_r_bc['id'] = $nat['id'];
				$syntax['modified'] = "modified = CURRENT_TIMESTAMP, modified_by = {$_SESSION['id']}";
				$syntax['created'] = '';
				$syntax['pp']['modified'] = "modified = " . ( ($_POST['pp']['created']) ? "CURRENT_TIMESTAMP," : "0," ) . ' modified_by = '. $_SESSION['id'] . ',';
				$syntax['pp']['created'] = "created = " . ( ($_POST['pp']['created']) ? "'{$_POST['pp']['created']}'" : "CURRENT_TIMESTAMP" );
				$syntax['r']['modified'] = "modified = " . ( ($_POST['r']['created']) ? "CURRENT_TIMESTAMP," : "0," ) . ' modified_by = '. $_SESSION['id'] . ',';
				$syntax['r']['created'] = "created = " . ( ($_POST['r']['created']) ? "'{$_POST['r']['created']}'" : "CURRENT_TIMESTAMP" );
				$syntax['bc']['modified'] = "modified = " . ( ($_POST['bc']['created']) ? "CURRENT_TIMESTAMP," : "0," ) . ' modified_by = '. $_SESSION['id'] . ',';
				$syntax['bc']['created'] = "created = " . ( ($_POST['bc']['created']) ? "'{$_POST['bc']['created']}'" : "CURRENT_TIMESTAMP" );
			}
		}
	
		/* Start MySQL transaction */
		mysql_query('START TRANSACTION');
	
		/* Insert data into the nationals table */
		if ($_SESSION['permission_nat']) {
			$nat['sql'] = "	{$nat['handler']} nationals
							SET		{$nat['id']}
									name_first = '{$_POST['name_first']}',
									name_last = '{$_POST['name_last']}',
									name_maiden = '{$_POST['name_maiden']}',
									gender = '{$_POST['gender']}',
									address_1 = '{$_POST['address_1']}',
									address_2 = '{$_POST['address_2']}',
									city = '{$_POST['city']}',
									province = '{$_POST['province']}',
									postal_code = '{$_POST['postal_code']}',
									tel = '{$_POST['tel']}',
									tel_2 = '{$_POST['tel_2']}',
									email = '{$_POST['email']}',
									comments = '{$_POST['comments']}',
									validity_end_date = '{$_POST['validity_end_date']}',
									{$syntax['modified']}
									{$syntax['created']}
							{$nat['where']}";
			if (!mysql_query($nat['sql'])) {
				$output['result'] = false;
				$output['error']['mysql'] = mysql_error();
				print json_encode($output);
				exit;
			} else {
				if (mysql_insert_id()) $pp_r_bc['id'] = "id = ".mysql_insert_id().",";
			}
		}
	
		/* Insert data into the passports table */
		if ($_SESSION['permission_pp'] and has_values($_POST['pp'])) {
			if ($_SESSION['permission_pp'] > 1 and $_POST['pp']['delete'] == '1') {
				$pp['sql'] = "DELETE FROM passports WHERE id = {$_GET['edit']} LIMIT 1";
			} else {
				$pp['sql'] = "		{$pp_r_bc['handler']} passports
								SET		{$pp_r_bc['id']}
										date_received = '{$_POST['pp']['date_received']}',
										date_sent_to_pos = '{$_POST['pp']['date_sent_to_pos']}',
										date_received_from_pos = '{$_POST['pp']['date_received_from_pos']}',
										date_activated = '{$_POST['pp']['date_activated']}',
										pp_number = '{$_POST['pp']['pp_number']}',
										date_sent_to_applicant = '{$_POST['pp']['date_sent_to_applicant']}',
										{$syntax['pp']['modified']}
										{$syntax['pp']['created']}";
			}
			if (!mysql_query($pp['sql'])) {
				$output['result'] = false;
				$output['error']['mysql'] = mysql_error();
				print json_encode($output);
				exit;
			}
		}
	
		/* Insert data into the restorations database */
		if ($_SESSION['permission_rest'] and has_values($_POST['r'])) {
			if ($_SESSION['permission_rest'] > 1 and $_POST['r']['delete'] == '1') {
				$r['sql'] = "DELETE FROM restorations WHERE id = {$_GET['edit']} LIMIT 1";
			} else {
				$r['sql'] = "		{$pp_r_bc['handler']} restorations 
								SET		{$pp_r_bc['id']}
										date_received = '{$_POST['r']['date_received']}',
										date_sent_to_pos = '{$_POST['r']['date_sent_to_pos']}',
										date_received_from_pos = '{$_POST['r']['date_received_from_pos']}',
										date_rest_cert_issued = '{$_POST['r']['date_rest_cert_issued']}',
										rest_cert_number = '{$_POST['r']['rest_cert_number']}',
										date_sent_to_applicant = '{$_POST['r']['date_sent_to_applicant']}',
										{$syntax['r']['modified']}
										{$syntax['r']['created']}";
			}
			if (!mysql_query($r['sql'])) {
				$output['result'] = false;
				$output['error']['mysql'] = mysql_error();
				print json_encode($output);
				exit;
			}
		}
	
		/* Insert data into the birth_certificates database */
		if ($_SESSION['permission_birth_cert'] and has_values($_POST['bc'])) {
			if ($_SESSION['permission_birth_cert'] > 1 and $_POST['bc']['delete'] == '1') {
				$bc['sql'] = "DELETE FROM birth_certificates WHERE id = {$_GET['edit']} LIMIT 1";
			} else {
				$bc['sql'] = "		{$pp_r_bc['handler']} birth_certificates 
								SET		{$pp_r_bc['id']}
										date_received = '{$_POST['bc']['date_received']}',
										date_sent_to_pos = '{$_POST['bc']['date_sent_to_pos']}',
										date_received_from_pos = '{$_POST['bc']['date_received_from_pos']}',
										pin_number = '{$_POST['bc']['pin_number']}',
										birth_cert_number = '{$_POST['bc']['birth_cert_number']}',
										date_sent_to_applicant = '{$_POST['bc']['date_sent_to_applicant']}',
										date_of_birth = '{$_POST['bc']['date_of_birth']}',
										{$syntax['bc']['modified']}
										{$syntax['bc']['created']}";
			}
			if (!mysql_query($bc['sql'])) {
				$output['result'] = false;
				$output['error']['mysql'] = mysql_error();
				print json_encode($output);
				exit;
			}
		}
	
		/* Commit the changes to the database */
		if (mysql_query('COMMIT')) {
			$output['result'] = true;
			print json_encode($output);
		} else {
			$output['result'] = false;
			$output['error']['mysql'] = mysql_error();
			print json_encode($output);
			exit;
		}
		exit;
	}


	/* Set default values for adding a national */
	$action		= '?add';
	$verb_present	= 'Add';
	$verb_past	= 'added';
	$radios['gender']['F'] = ' checked="checked"';

	/* An existing national is being edited */
	if (isset($_GET['edit'])) {
		$_GET['edit'] = (int) $_GET['edit'];
		$nat['sql'] = "SELECT * FROM nationals WHERE id = {$_GET['edit']} LIMIT 1";
		if ($nat['query'] = mysql_query($nat['sql']) and mysql_num_rows($nat['query']) == 1) { // National exists
				
			/* Display this national's information */
			$nat['results'] = mysql_fetch_assoc($nat['query']);
			$action		= '?edit='.$_GET['edit'];
			$verb_present	= 'Edit';
			$verb_past	= 'edited';
			unset($radios);
			$radios['gender'][$nat['results']['gender']]		= ' checked="checked"';
			$radios['province'][$nat['results']['province']]	= ' selected="selected"';

			$pp['sql'] = "SELECT * FROM passports WHERE id = {$_GET['edit']} LIMIT 1";
			$r['sql'] = "SELECT * FROM restorations WHERE id = {$_GET['edit']} LIMIT 1";
			$bc['sql'] = "SELECT * FROM birth_certificates WHERE id = {$_GET['edit']} LIMIT 1";
			if ($pp['query'] = mysql_query($pp['sql']) and $r['query'] = mysql_query($r['sql']) and $bc['query'] = mysql_query($bc['sql'])) {
				$nat['pp']['results'] = mysql_fetch_assoc($pp['query']);
				$nat['r']['results'] = mysql_fetch_assoc($r['query']);
				$nat['bc']['results'] = mysql_fetch_assoc($bc['query']);
			}
		}
	}
	
	$layout->print_header();
?>
<script type="text/javascript">
//<![CDATA[
$(function(){
	$('#postal_code').mask('a9a 9a9');
	$('#tel, #tel_2').mask('(999) 999-9999');

	$('.date_picker').datepicker();

	$('#validity_end_date').datepicker( {
		minDate: 'y m +1d'
	});

//	$('#bc_date_of_birth').datepicker( {
//		maxDate: 'y m d',
//		yearRange: '-75:0'
//	});

	$('form').ajaxForm({
		dataType	: 'json',
		success 	: function (data) {
			$('form .error').fadeOut('slow', function () { $(this).remove() });
			if (data.result == true) {
				$.prompt($('#name_first').val() + ' ' + $('#name_last').val() + ' was <?php print $verb_past ?> successfully.', { buttons: { OK: true }, callback: function(){ <?php if (isset($_GET['add'])) { ?>location.href="<?php print $_SERVER['PHP_SELF'] ?>?add";<?php } else { ?>location.href="<?php print $_SERVER['PHP_SELF'] ?>";<?php } ?> } });
			} else {
				if (data.error.validation) {
					var msg = 'Your input is invalid; please check it and try again.';
					$.prompt(
						msg,
						{ buttons: { 'Try again': true },
						callback: function () {
							$(':submit').removeAttr('disabled').attr('value','<?php print $verb_present ?> this user');
							for (var i in data.error.validation) {
								if (data.error.validation[i]) {
									$('[name="'+i+'"]').after('<span class="error no-display">'+data.error.validation[i]+'</span>');
									$('form .error').fadeIn('normal');
									for (var j in data.error.validation[i]) {
										if (/^-?\d+$/.test(j) == false) {
											$('[name="'+i+'['+j+']"]').after('<span class="error">'+data.error.validation[i][j]+'</span>');
										}
									}
								}
							}
						}
					});
				}
				if (data.error.mysql) {
					var msg = '<p>The following error has occurried:<br />' + data.error.mysql + '</p>Please try again in a few moments or contact your administrator.';
					$.prompt(msg, { buttons: { 'Try again': true }, callback: function(){ $(':submit').removeAttr('disabled').attr('value','<?php print $verb_present ?> this user'); }, opacity: 0.9 });
				}
			}
		}
	}); 
});
//]]>
</script>

<h2><?php print $verb_present ?> a National</h2>
<form method="post" action="<?php print $_SERVER['PHP_SELF'].$action ?>">
	<fieldset>
<?php
	if ($_SESSION['permission_nat'] < 1) $nat_disabled = ' disabled="disabled"';
?>
		<fieldset>
			<ul>
				<li><label for="name_first">First name</label><input type="text" name="name_first" id="name_first" value="<?php print $nat['results']['name_first'] ?>"<?php print $nat_disabled ?> /></li>
				<li><label for="name_last">Last name</label><input type="text" name="name_last" id="name_last" value="<?php print $nat['results']['name_last'] ?>"<?php print $nat_disabled ?> /></li>
				<li><label for="name_maiden">Maiden name</label><input type="text" name="name_maiden" id="name_maiden" value="<?php print $nat['results']['name_maiden'] ?>"<?php print $nat_disabled ?> /></li>
				<li>
					<label>Gender</label>
					<fieldset class="horizontal_list">
						<label><input name="gender" type="radio" value="F"<?php print $radios['gender']['F'] ?><?php print $nat_disabled ?> /> Female</label>
						<label><input name="gender" type="radio" value="M"<?php print $radios['gender']['M'] ?><?php print $nat_disabled ?> /> Male</label>
					</fieldset>
				</li>
				<li><label for="address_1">Address line 1</label><input type="text" name="address_1" id="address_1" value="<?php print $nat['results']['address_1'] ?>"<?php print $nat_disabled ?> /></li>
				<li><label for="address_2">Address line 2</label><input type="text" name="address_2" id="address_2" value="<?php print $nat['results']['address_2'] ?>"<?php print $nat_disabled ?> /></li>
				<li><label for="city">City</label><input type="text" name="city" id="city" value="<?php print $nat['results']['city'] ?>"<?php print $nat_disabled ?> /></li>
				<li><label for="province">Province</label>
					<select name="province" id="province"<?php print $nat_disabled ?>>
<?php
	foreach ($provinces_territories AS $key => $value) {
?>
						<option value="<?php print $key ?>"<?php print $radios['province'][$key] ?>><?php print $value[1] ?></option>
<?php
	}
?>
					</select></li>
				<li><label for="postal_code">Postal code</label><input type="text" name="postal_code" id="postal_code" maxlength="7" value="<?php print $nat['results']['postal_code'] ?>"<?php print $nat_disabled ?> /></li>
				<li><label for="tel">Telephone</label><input type="text" name="tel" id="tel" maxlength="14" value="<?php print $nat['results']['tel'] ?>"<?php print $nat_disabled ?> /></li>
				<li><label for="tel_2">Telephone <abbr title="secondary">2&deg;</abbr></label><input type="text" name="tel_2" id="tel_2" maxlength="14" value="<?php print $nat['results']['tel_2'] ?>"<?php print $nat_disabled ?> /></li>
				<li><label for="email">Email</label><input type="text" name="email" id="email" value="<?php print $nat['results']['email'] ?>"<?php print $nat_disabled ?> /></li>
				<li><label for="comments">Comments</label><textarea name="comments" id="comments"<?php print $nat_disabled ?>><?php print $nat['results']['comments'] ?></textarea></li>
				<li><label for="validity_end_date">Valid until</label><input name="validity_end_date" type="text" id="validity_end_date" value="<?php if ($nat['results']['validity_end_date'] != '0000-00-00') print $nat['results']['validity_end_date'] ?>"<?php print $nat_disabled ?> /></li>
			</ul>
<?php
	if (!isset($_GET['add'])) {
?>
			<fieldset>
				<h4>Dates &amp; times</h4>
				<ul>
					<li><label>Last modified</label><strong><?php print $nat['results']['modified'] != 0 ? $nat['results']['modified'] : 'Never' ?></strong></li>
					<li><label>Last modified by</label><strong><?php print $nat['results']['modified_by'] != 0 ? mysql_result(mysql_query("SELECT concat(name_first, ' ', name_last) FROM users WHERE id = {$nat['results']['modified_by']}"), 0) : 'No one' ?></strong></li>
					<li><label>Created</label><input name="created" type="hidden" value="<?php print $nat['results']['created'] ?>" /><strong><?php print $nat['results']['created'] ?></strong></li>
				</ul>
			</fieldset>
<?php
	}
?>
		</fieldset>
<?php
	if ($_SESSION['permission_pp'] < 1) $pp_disabled = ' disabled="disabled"';
?>
		<fieldset id="pp_details" class="wide">
			<h3>Passport application details</h3>
			<ul>
				<li><label for="pp_date_received">Date received</label><input name="pp[date_received]" type="text" class="date_picker" id="pp_date_received" value="<?php if ($nat['pp']['results']['date_received'] != '0000-00-00') print $nat['pp']['results']['date_received'] ?>"<?php print $pp_disabled ?> /></li>
				<li><label for="pp_date_sent_to_pos">Date sent to <abbr title="Port of Spain">POS</abbr></label><input name="pp[date_sent_to_pos]" type="text" class="date_picker" id="pp_date_sent_to_pos" value="<?php if ($nat['pp']['results']['date_sent_to_pos'] != '0000-00-00') print $nat['pp']['results']['date_sent_to_pos'] ?>"<?php print $pp_disabled ?> /></li>
				<li><label for="pp_date_received_from_pos">Date received from <abbr title="Port of Spain">POS</abbr></label><input name="pp[date_received_from_pos]" type="text" class="date_picker" id="pp_date_received_from_pos" value="<?php if ($nat['pp']['results']['date_received_from_pos'] != '0000-00-00') print $nat['pp']['results']['date_received_from_pos'] ?>"<?php print $pp_disabled ?> /></li>
				<li><label for="pp_date_activated">Date activated</label><input name="pp[date_activated]" type="text" class="date_picker" id="pp_date_activated" value="<?php if ($nat['pp']['results']['date_activated'] != '0000-00-00') print $nat['pp']['results']['date_activated'] ?>"<?php print $pp_disabled ?> /></li>
				<li><label for="pp_pp_number">Passport number</label><input name="pp[pp_number]" type="text" id="pp_pp_number" value="<?php print $nat['pp']['results']['pp_number'] ?>"<?php print $pp_disabled ?> /></li>
				<li><label for="pp_date_sent_to_applicant">Date sent to applicant</label><input name="pp[date_sent_to_applicant]" type="text" class="date_picker" id="pp_date_sent_to_applicant" value="<?php if ($nat['pp']['results']['date_sent_to_applicant'] != '0000-00-00') print $nat['pp']['results']['date_sent_to_applicant'] ?>"<?php print $pp_disabled ?> /></li>
			</ul>
<?php
	if (!empty($nat['pp']['results']['created'])) {
?>
			<fieldset>
				<h4>Dates &amp; times</h4>
				<ul>
					<li><label>Last modified</label><strong><?php print $nat['pp']['results']['modified'] != 0 ? $nat['pp']['results']['modified'] : 'Never' ?></strong></li>
					<li><label>Last modified by</label><strong><?php print $nat['pp']['results']['modified_by'] != 0 ? mysql_result(mysql_query("SELECT concat(name_first, ' ', name_last) FROM users WHERE id = {$nat['pp']['results']['modified_by']}"), 0) : 'No one' ?></strong></li>
					<li><label>Created</label><input name="pp[created]" type="hidden" value="<?php print $nat['pp']['results']['created'] ?>" /><strong><?php print $nat['pp']['results']['created'] ?></strong></li>
				</ul>
			</fieldset>
<?php
		if ($_SESSION['permission_pp'] > 1) {
?>
			<fieldset>
				<h4>Passport details record control</h4>
				<ul>
					<li><label for="pp_delete">Delete these details?</label><input name="pp[delete]" id="pp_delete" type="checkbox" value="1" /></li>
				</ul>
			</fieldset>
<?php
		}
	}
?>
		</fieldset>
<?php
	if ($_SESSION['permission_rest'] < 1) $r_disabled = ' disabled="disabled"';
?>
		<fieldset id="r_details" class="wide">
			<h3>Restoration application details</h3>
			<ul>
				<li><label for="r_date_received">Date received</label><input name="r[date_received]" type="text" class="date_picker" id="r_date_received" value="<?php if ($nat['r']['results']['date_received'] != '0000-00-00') print $nat['r']['results']['date_received'] ?>"<?php print $r_disabled ?> /></li>
				<li><label for="r_date_sent_to_pos">Date sent to <abbr title="Port of Spain">POS</abbr></label><input name="r[date_sent_to_pos]" type="text" class="date_picker" id="r_date_sent_to_pos" value="<?php if ($nat['r']['results']['date_sent_to_pos'] != '0000-00-00') print $nat['r']['results']['date_sent_to_pos'] ?>"<?php print $r_disabled ?> /></li>
				<li><label for="r_date_received_from_pos">Date received from <abbr title="Port of Spain">POS</abbr></label><input name="r[date_received_from_pos]" type="text" class="date_picker" id="r_date_received_from_pos" value="<?php if ($nat['r']['results']['date_received_from_pos'] != '0000-00-00') print $nat['r']['results']['date_received_from_pos'] ?>"<?php print $r_disabled ?> /></li>
				<li><label for="r_date_rest_cert_issued">Date restoration <abbr title="certificate">cert.</abbr> issued</label><input name="r[date_rest_cert_issued]" type="text" class="date_picker" id="r_date_rest_cert_issued" value="<?php if ($nat['r']['results']['date_rest_cert_issued'] != '0000-00-00') print $nat['r']['results']['date_rest_cert_issued'] ?>"<?php print $r_disabled ?> /></li>
				<li><label for="r_rest_cert_number">Restoration <abbr title="certificate">cert.</abbr> number</label><input name="r[rest_cert_number]" type="text" id="r_rest_cert_number" value="<?php print $nat['r']['results']['rest_cert_number'] ?>"<?php print $r_disabled ?> /></li>
				<li><label for="r_date_sent_to_applicant">Date sent to applicant</label><input name="r[date_sent_to_applicant]" type="text" class="date_picker" id="r_date_sent_to_applicant" value="<?php if ($nat['r']['results']['date_sent_to_applicant'] != '0000-00-00') print $nat['r']['results']['date_sent_to_applicant'] ?>"<?php print $r_disabled ?> /></li>
			</ul>
<?php
	if (!empty($nat['r']['results']['created'])) {
?>
			<fieldset>
				<h4>Dates &amp; times</h4>
				<ul>
					<li><label>Last modified</label><strong><?php print $nat['r']['results']['modified'] != 0 ? $nat['r']['results']['modified'] : 'Never' ?></strong></li>
					<li><label>Last modified by</label><strong><?php print $nat['r']['results']['modified_by'] != 0 ? mysql_result(mysql_query("SELECT concat(name_first, ' ', name_last) FROM users WHERE id = {$nat['r']['results']['modified_by']}"), 0) : 'No one' ?></strong></li>
					<li><label>Created</label><input name="r[created]" type="hidden" value="<?php print $nat['r']['results']['created'] ?>" /><strong><?php print $nat['r']['results']['created'] ?></strong></li>
				</ul>
			</fieldset>
<?php
		if ($_SESSION['permission_rest'] > 1) {
?>
			<fieldset>
				<h4>Restoration details record control</h4>
				<ul>
					<li><label for="r_delete">Delete these details?</label><input name="r[delete]" id="r_delete" type="checkbox" value="1" /></li>
				</ul>
			</fieldset>
<?php
		}
	}
?>
		</fieldset>
<?php
	if ($_SESSION['permission_birth_cert'] < 1) $bc_disabled = ' disabled="disabled"';
?>
		<fieldset id="bc_details" class="wide">
			<h3>Birth certificate application details</h3>
			<ul>
				<li><label for="bc_date_received">Date received</label><input name="bc[date_received]" type="text" class="date_picker" id="bc_date_received" value="<?php if ($nat['bc']['results']['date_sent_to_pos'] != '0000-00-00') print $nat['bc']['results']['date_received'] ?>"<?php print $bc_disabled ?> /></li>
				<li><label for="bc_date_sent_to_pos">Date sent to <abbr title="Port of Spain">POS</abbr></label><input name="bc[date_sent_to_pos]" type="text" class="date_picker" id="bc_date_sent_to_pos" value="<?php if ($nat['bc']['results']['date_sent_to_pos'] != '0000-00-00') print $nat['bc']['results']['date_sent_to_pos'] ?>"<?php print $bc_disabled ?> /></li>
				<li><label for="bc_date_received_from_pos">Date received from <abbr title="Port of Spain">POS</abbr></label><input name="bc[date_received_from_pos]" type="text" class="date_picker" id="bc_date_received_from_pos" value="<?php if ($nat['bc']['results']['date_received_from_pos'] != '0000-00-00') print $nat['bc']['results']['date_received_from_pos'] ?>"<?php print $bc_disabled ?> /></li>
				<li><label for="bc_pin_number">Pin number</label><input name="bc[pin_number]" type="text" id="bc_pin_number" value="<?php print $nat['bc']['results']['pin_number'] ?>"<?php print $bc_disabled ?> /></li>
				<li><label for="bc_birth_cert_number">Birth certificate number</label><input name="bc[birth_cert_number]" type="text" id="bc_birth_cert_number" value="<?php print $nat['bc']['results']['birth_cert_number'] ?>"<?php print $bc_disabled ?> /></li>
				<li><label for="bc_date_sent_to_applicant">Date sent to applicant</label><input name="bc[date_sent_to_applicant]" type="text" class="date_picker" id="bc_date_sent_to_applicant" value="<?php if ($nat['bc']['results']['date_sent_to_applicant'] != '0000-00-00') print $nat['bc']['results']['date_sent_to_applicant'] ?>"<?php print $bc_disabled ?> /></li>
				<li><label for="bc_date_of_birth">Date of birth</label><input name="bc[date_of_birth]" type="text" id="bc_date_of_birth" value="<?php if ($nat['bc']['results']['date_of_birth'] != '0000-00-00') print $nat['bc']['results']['date_of_birth'] ?>"<?php print $bc_disabled ?> /></li>
			</ul>
<?php
	if (!empty($nat['bc']['results']['created'])) {
?>
			<fieldset>
				<h4>Dates &amp; times</h4>
				<ul>
					<li><label>Last modified</label><strong><?php print $nat['bc']['results']['modified'] != 0 ? $nat['bc']['results']['modified'] : 'Never' ?></strong></li>
					<li><label>Last modified by</label><strong><?php print $nat['bc']['results']['modified_by'] != 0 ? mysql_result(mysql_query("SELECT concat(name_first, ' ', name_last) FROM users WHERE id = {$nat['bc']['results']['modified_by']}"), 0) : 'No one' ?></strong></li>
					<li><label>Created</label><input name="bc[created]" type="hidden" value="<?php print $nat['bc']['results']['created'] ?>" /><strong><?php print $nat['bc']['results']['created'] ?></strong></li>
				</ul>
			</fieldset>
<?php
		if ($_SESSION['permission_birth_cert'] > 1) {
?>
			<fieldset>
				<h4>Birth certificate  details record control</h4>
				<ul>
					<li><label for="bc_delete">Delete these details?</label><input name="bc[delete]" id="bc_delete" type="checkbox" value="1" /></li>
				</ul>
			</fieldset>
<?php
		}
	}
?>
		</fieldset>
		<fieldset class="controls">
			<input type="button" value="Back" onclick="history.back()" />
			<input type="reset" value="Reset" />
<?php
	if ($_SESSION['permission_nat'] or $_SESSION['permission_pp'] or $_SESSION['permission_rest'] or $_SESSION['permission_birth_cert']) {
?>
			<input type="submit" value="<?php print $verb_present ?> this national" />
<?php
	}
?>
		</fieldset>
	</fieldset>
</form>
<?php
	$layout->print_footer();
} elseif (isset($_GET['search'])) {
	if ($_REQUEST['delete']) {
		$_REQUEST['delete'] = (int) $_REQUEST['delete'];
		if (mysql_query("DELETE FROM nationals WHERE id = {$_REQUEST['delete']} LIMIT 1") and mysql_affected_rows() == 1) {
			print true;
		} else {
			print mysql_error();
		}
		exit;
	}

	$nat['sql'] = "	SELECT	n.id, n.name_first, n.name_last, n.name_maiden, n.city, n.province, n.postal_code, n.tel, n.email,
							pp.id AS pp, r.id AS r, bc.id AS bc
					FROM		nationals AS n
					LEFT JOIN	passports AS pp ON n.id = pp.id
					LEFT JOIN	restorations AS r ON n.id = r.id
					LEFT JOIN	birth_certificates AS bc ON n.id = bc.id
					WHERE	n.name_first LIKE '%{$_POST['name_first']}%' AND
							n.name_last LIKE '%{$_POST['name_last']}%'
					ORDER BY	n.name_last, n.name_first, n.name_maiden, n.city, n.province, n.postal_code";
	if ($nat['query'] = mysql_query($nat['sql'])) {
		$layout->print_header();
?>
<script type="text/javascript">
//<![CDATA[
function delete_nat(id, name) {
	$.prompt(
		'Are you sure you want to delete '+name+'?',
		{ buttons: { Yes: true, No: false },
		callback: function(v,m) {
			if (v) {
				$.post('<?php print $_SERVER['PHP_SELF'] ?>?search',
				{ 'delete': id },
				function (result) {
					if (result == true) {
						$.prompt(name+' was deleted successfully.', { callback: function(){ $('tr#nat_'+id).fadeOut('normal', function() { $(this).remove(); } ); } });
					} else {
						var msg = '<p>The following error has occurried:<br />' + result + '</p>Please try again in a few moments or contact your administrator.';
						$.prompt(msg, { buttons: { 'Try again': true } });
					}
				});
			}
		}
	});
}
//]]>
</script>
<h2>Search Results</h2>
<p>Results found: <strong><?php print mysql_num_rows($nat['query']) ?></strong></p>
<?php
		if (mysql_num_rows($nat['query'])) {
?>
<table summary="Table of the Nationals Database" style="width:100%;">
	<thead>
		<tr>
			<th scope="col" style="width:100%;">Last name</th>
			<th scope="col">First name</th>
			<th scope="col">Maiden name</th>
			<th scope="col">City</th>
			<th scope="col">Province</th>
			<th scope="col">Postal code</th>
			<th scope="col">Telephone</th>
			<th scope="col">Email</th>
			<th scope="col"><abbr title="Passport application record">PP</abbr></th>
			<th scope="col"><abbr title="Restoration application record">R</abbr></th>
			<th scope="col"><abbr title="Birth certificate application record">BC</abbr></th>
			<th scope="col">&nbsp;</th>
			<th scope="col"><?php if ($_SESSION['permission_nat'] > 0) { ?><a href="<?php print $_SERVER['PHP_SELF'] ?>?add" title="Add a national" class="char_button">+</a><?php } else { ?>&nbsp;<?php } ?></th>
		</tr>
	</thead>
	<tbody>
<?php
			while ($nat['results'] = mysql_fetch_assoc($nat['query'])) {
?>
		<tr id="nat_<?php print $nat['results']['id'] ?>">
			<td><strong><?php print $nat['results']['name_last'] ?></strong></td>
			<td><?php print $nat['results']['name_first'] ?></td>
			<td><?php print $nat['results']['name_maiden'] ?></td>
			<td><?php print $nat['results']['city'] ?></td>
			<td><?php print $provinces_territories[$nat['results']['province']][0] ?></td>
			<td><?php print $nat['results']['postal_code'] ?></td>
			<td style="white-space:nowrap;"><?php print $nat['results']['tel'] ?></td>
			<td style="max-width:8em; overflow:hidden;"><?php if (!empty($nat['results']['email'])) { ?><a href="mailto:<?php print $nat['results']['email'] ?>"><?php print $nat['results']['email'] ?></a><?php } else { ?>&nbsp;<?php } ?></td>
			<td style="text-align:center"><?php print is_null($nat['results']['pp']) ? '<abbr title="No passport application record">N</abbr>' : '<abbr title="Passport application record present">Y</abbr>' ?></td>
			<td style="text-align:center"><?php print is_null($nat['results']['r']) ? '<abbr title="No restoration application record">N</abbr>' : '<abbr title="Restoration application record present">Y</abbr>' ?></td>
			<td style="text-align:center"><?php print is_null($nat['results']['bc']) ? '<abbr title="No birth certificate application record">N</abbr>' : '<abbr title="Birth certificate application record present">Y</abbr>' ?></td>
			<td><a href="<?php print $_SERVER['PHP_SELF'] ?>?edit=<?php print $nat['results']['id'] ?>" title="<?php if ($_SESSION['permission_nat']) { ?>Edit<?php } else { ?>View<?php } ?>" class="char_button">&Delta;</a></td>
			<td><?php if ($_SESSION['permission_nat'] > 1) { ?><a href="<?php print $_SERVER['PHP_SELF'] ?>?delete=<?php print $nat['results']['id'] ?>" title="Delete" class="char_button" onclick="delete_nat(<?php print $nat['results']['id'] ?>, '<?php print $nat['results']['name_first'] ?> <?php print $nat['results']['name_last'] ?>'); return false;">&times;</a><?php } else { ?>&nbsp;<?php } ?></td>
		</tr>
<?php
			}
?>
	</tbody>
</table>
<div class="controls" style="text-align:left">
	<input type="button" value="Back" onclick="history.back()" />
</div>
<?php
		} else {
?>
No nationals matching that criteria were found in the database.
<?php
		}
		$layout->print_footer();
	}
} else {
	$layout->print_header();
?>
<h2>Search</h2>
<form method="post" action="<?php print $_SERVER['PHP_SELF'] ?>?search">
	<fieldset>
		<ul>
			<li><label for="name_first">First name</label><input type="text" name="name_first" id="name_first" /></li>
			<li><label for="name_last">Last name</label><input type="text" name="name_last" id="name_last" /></li>
		</ul>
	</fieldset>
	<fieldset>
		<fieldset class="controls">
			<input type="button" value="Back" onclick="history.back()" />
			<input type="reset" value="Reset" />
			<input type="submit" value="Search" />
		</fieldset>
	</fieldset>
</form>
<?php
	$layout->print_footer();
}
?>