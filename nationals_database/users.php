<?php
require_once('settings.php');

$authentication->authenticate();
$authentication->is_admin(true);

if ($_POST['is_admin'] == '1') {
	$_POST['can_export'] = 1;
	$_POST['permission_nat'] = 2;
	$_POST['permission_pp'] = 2;
	$_POST['permission_rest'] = 2;
	$_POST['permission_birth_cert'] = 2;
}

if (isset($_GET['add']) or isset($_GET['edit'])) {
	/* Set default values */
	$action		= '?add';
	$verb_present	= 'Add';
	$verb_past	= 'added';
	$radios['permission_nat'][0]			= ' checked="checked"';
	$radios['permission_pp'][0]			= ' checked="checked"';
	$radios['permission_rest'][0] 		= ' checked="checked"';
	$radios['permission_birth_cert'][0]	= ' checked="checked"';

	if (isset($_GET['add'])) {
		if ($_POST) {
			$users['sql'] = "	INSERT INTO users (sign_in, password, name_first, name_last, email, is_admin, permission_nat, permission_pp, permission_rest, permission_birth_cert, modified, created)
							VALUES ('{$_POST['sign_in']}', '".sha1($_POST['password'])."', '{$_POST['name_first']}', '{$_POST['name_last']}', '{$_POST['email']}', '{$_POST['is_admin']}', '{$_POST['permission_nat']}', '{$_POST['permission_pp']}', '{$_POST['permission_rest']}', '{$_POST['permission_birth_cert']}', '0', CURRENT_TIMESTAMP )";
			if (mysql_query($users['sql'])) {
				print true;
			} else {
				print mysql_error();
			}
			exit;
		}
	} elseif (isset($_GET['edit'])) {
		$_GET['edit'] = (int) $_GET['edit'];
		$users['sql'] = "SELECT * FROM users WHERE id = {$_GET['edit']} LIMIT 1";
		if ($users['query'] = mysql_query($users['sql']) and mysql_num_rows($users['query']) == 1) {
			if ($_POST) {
				if ($_POST['password'] != '') {
					$password_sql = "password = '".sha1($_POST['password'])."',";
				}
				$users['sql'] = "	UPDATE	users 
								SET		sign_in = '{$_POST['sign_in']}',
										{$password_sql}
										name_first = '{$_POST['name_first']}',
										name_last = '{$_POST['name_last']}',
										email = '{$_POST['email']}',
										is_admin = '".(int)$_POST['is_admin']."',
										can_export = '".(int)$_POST['can_export']."',
										permission_nat = '{$_POST['permission_nat']}',
										permission_pp = '{$_POST['permission_pp']}',
										permission_rest = '{$_POST['permission_rest']}',
										permission_birth_cert = '{$_POST['permission_birth_cert']}',
										modified = CURRENT_TIMESTAMP
								WHERE	id = {$_GET['edit']}
								LIMIT	1";
				if (mysql_query($users['sql'])) {
					print true;
				} else {
					print mysql_error();
				}
				exit;
			}
			$users['results'] = mysql_fetch_assoc($users['query']);
			$action		= '?edit='.$_GET['edit'];
			$verb_present	= 'Edit';
			$verb_past	= 'edited';
			unset($radios);
			$radios['permission_nat'][$users['results']['permission_nat']]				= ' checked="checked"';
			$radios['permission_pp'][$users['results']['permission_pp']]				= ' checked="checked"';
			$radios['permission_rest'][$users['results']['permission_rest']] 			= ' checked="checked"';
			$radios['permission_birth_cert'][$users['results']['permission_birth_cert']]	= ' checked="checked"';
		}
	}
	
	$layout->print_header();
?>
<script type="text/javascript">
//<![CDATA[
$(function(){
	$('form').submit(function () {
		$.post('<?php print $_SERVER['PHP_SELF'].$action ?>', {
			sign_in: $('#sign_in').val(),
			password: $('#password').val(),
			name_first: $('#name_first').val(),
			name_last: $('#name_last').val(),
			email: $('#email').val(),
			is_admin: $('#is_admin:checked').val(),
			can_export: $('#can_export:checked').val(),
			permission_nat: $('input[name=permission_nat]:checked').val(),
			permission_pp: $('input[name=permission_pp]:checked').val(),
			permission_rest: $('input[name=permission_rest]:checked').val(),
			permission_birth_cert: $('input[name=permission_birth_cert]:checked').val()
		},
		function (result) {
			if (result == true) {
				$.prompt($('#name_first').val() + ' ' + $('#name_last').val() + ' was <?php print $verb_past ?> successfully.', { buttons: { OK: true }, callback: function(){ location.href = '<?php print $_SERVER['PHP_SELF'] ?>'; } });
			} else {
				var msg = '<p>The following error has occurried:<br />' + result + '</p>Please try again in a few moments or contact your administrator.';
				$.prompt(msg, { buttons: { 'Try again': true }, callback: function(){ $(':submit').removeAttr('disabled').attr('value','<?php $verb_present ?> this user'); }, opacity: 0.9 });
			}
		});
		return false;
	});
});
//]]>
</script>

<h2><?php print $verb_present ?> a User</h2>
<form method="post" action="<?php print $_SERVER['PHP_SELF'].$action ?>">
	<fieldset>
		<fieldset>
			<ul>
				<li><label for="sign_in">Sign in</label><input type="text" name="sign_in" id="sign_in" value="<?php print $users['results']['sign_in'] ?>" /></li>
				<li><label for="password">Password</label><input type="password" name="password" id="password" /></li>
				<li><label for="name_first">First name</label><input type="text" name="name_first" id="name_first" value="<?php print $users['results']['name_first'] ?>" /></li>
				<li><label for="name_last">Last name</label><input type="text" name="name_last" id="name_last" value="<?php print $users['results']['name_last'] ?>" /></li>
				<li><label for="email">Email</label><input type="text" name="email" id="email" value="<?php print $users['results']['email'] ?>" /></li>
				<li><label for="is_admin">Administrator</label><input type="checkbox" name="is_admin" id="is_admin" value="1"<?php if ($users['results']['is_admin']) { ?> checked="checked"<?php } ?> /></li>
				<li><label for="can_export">Export</label><input type="checkbox" name="can_export" id="can_export" value="1"<?php if ($users['results']['can_export']) { ?> checked="checked"<?php } ?> /></li>
			</ul>
		</fieldset>
		<fieldset>
			<table id="database_permisions">
				<caption>Database permissions</caption>
				<thead>
					<tr>
						<th scope="col">Details</th>
						<th scope="col" align="center">View</th>
						<th scope="col" align="center">View, add &amp; edit</th>
						<th scope="col" align="center">View, add, edit &amp; delete</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>Contact</th>
						<td align="center"><input type="radio" name="permission_nat" value="0" title="Contact details permission: view"<?php print $radios['permission_nat'][0] ?> /></td>
						<td align="center"><input type="radio" name="permission_nat" value="1" title="Contact details permission: view, add &amp; edit"<?php print $radios['permission_nat'][1] ?> /></td>
						<td align="center"><input type="radio" name="permission_nat" value="2" title="Contact details permission: view, add, edit &amp; delete"<?php print $radios['permission_nat'][2] ?> /></td>
					</tr>
					<tr>
						<th>Passport</th>
						<td align="center"><input type="radio" name="permission_pp" value="0" title="Passport details permission: view"<?php print $radios['permission_pp'][0] ?> /></td>
						<td align="center"><input type="radio" name="permission_pp" value="1" title="Passport details permission: view, add &amp; edit"<?php print $radios['permission_pp'][1] ?> /></td>
						<td align="center"><input type="radio" name="permission_pp" value="2" title="Passport details permission: view, add, edit &amp; delete"<?php print $radios['permission_pp'][2] ?> /></td>
					</tr>
					<tr>
						<th>Restoration</th>
						<td align="center"><input type="radio" name="permission_rest" value="0" title="Restoration details permission: view"<?php print $radios['permission_rest'][0] ?> /></td>
						<td align="center"><input type="radio" name="permission_rest" value="1" title="Restoration details permission: view, add &amp; edit"<?php print $radios['permission_rest'][1] ?> /></td>
						<td align="center"><input type="radio" name="permission_rest" value="2" title="Restoration details permission: view, add, edit &amp; delete"<?php print $radios['permission_rest'][2] ?> /></td>
					</tr>
					<tr>
						<th>Birth certificate</th>
						<td align="center"><input type="radio" name="permission_birth_cert" value="0" title="Birth certificate details permission: view"<?php print $radios['permission_birth_cert'][0] ?> /></td>
						<td align="center"><input type="radio" name="permission_birth_cert" value="1" title="Birth certificate details permission: view, add &amp; edit"<?php print $radios['permission_birth_cert'][1] ?> /></td>
						<td align="center"><input type="radio" name="permission_birth_cert" value="2" title="Birth certificate details permission: view, add, edit &amp; delete"<?php print $radios['permission_birth_cert'][2] ?> /></td>
					</tr>
				</tbody>
			</table>
		</fieldset>
<?php
	if (!isset($_GET['add'])) {
?>
			<fieldset>
				<h4>Dates &amp; times</h4>
				<ul>
					<li><label>Last visited</label><strong><?php print $users['results']['last_visited'] != 0 ? $users['results']['last_visited'] : 'Never' ?></strong></li>
					<li><label>Last modified</label><strong><?php print $users['results']['modified'] != 0 ? $users['results']['modified'] : 'Never' ?></strong></li>
					<li><label>Created</label><strong><?php print $users['results']['created'] ?></strong></li>
				</ul>
			</fieldset>
<?php
	}
?>
		<fieldset class="controls">
			<input type="button" value="Back" onclick="location.href='<?php print $_SERVER['PHP_SELF'] ?>'" />
			<input type="reset" value="Reset" />
			<input type="submit" value="<?php print $verb_present ?> this user" />
		</fieldset>
	</fieldset>
</form>
<?php
	$layout->print_footer();
} else {
	if ($_REQUEST['delete']) {
		$_REQUEST['delete'] = (int) $_REQUEST['delete'];
		if (mysql_query("DELETE FROM users WHERE id = {$_REQUEST['delete']} LIMIT 1") and mysql_affected_rows() == 1) {
			print true;
		} else {
			print mysql_error();
		}
		exit;
	}

	$users['sql'] = "SELECT * FROM users ORDER BY name_last";
	if ($users['query'] = mysql_query($users['sql'])) {
		$layout->print_header();
?>
<script type="text/javascript">
//<![CDATA[
function delete_user(id, name) {
	$.prompt(
		'Are you sure you want to delete '+name+'?',
		{ buttons: { Yes: true, No: false },
		callback: function(v,m) {
			if (v) {
				$.post('<?php print $_SERVER['PHP_SELF'] ?>',
				{ 'delete': id },
				function (result) {
					if (result == true) {
						$.prompt(name+' was deleted successfully.', { callback: function(){ $('tr#user_'+id).fadeOut('normal', function() { $(this).remove(); } ); } });
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
<h2>Users</h2>
<table summary="Table of users registered to access the Nationals Database">
	<thead>
		<tr>
			<th scope="col">ID</th>
			<th scope="col">Sign in</th>
			<th scope="col">Last name</th>
			<th scope="col">First name</th>
			<th scope="col">Email</th>
			<th scope="col">Admin</th>
			<th scope="col">Last visited</th>
			<th scope="col">Last modified</th>
			<th scope="col">Created</th>
			<th scope="col">&nbsp;</th>
			<th scope="col"><a href="<?php print $_SERVER['PHP_SELF'] ?>?add" title="Add a user" class="char_button">+</a></th>
		</tr>
	</thead>
	<tbody>
<?php
		while ($users['results'] = mysql_fetch_assoc($users['query'])) {
?>
		<tr id="user_<?php print $users['results']['id'] ?>">
			<td><?php print $users['results']['id'] ?></td>
			<td><?php print $users['results']['sign_in'] ?></td>
			<td><strong><?php print $users['results']['name_last'] ?></strong></td>
			<td><?php print $users['results']['name_first'] ?></td>
			<td><?php print $users['results']['email'] ?></td>
			<td><?php print $users['results']['is_admin'] ? 'Yes' : 'No' ?></td>
			<td><?php print $users['results']['last_visited'] != 0 ? $users['results']['last_visited'] : 'Never' ?></td>
			<td><?php print $users['results']['modified'] != 0 ? $users['results']['modified'] : 'Never' ?></td>
			<td><?php print $users['results']['created'] ?></td>
			<td><a href="<?php print $_SERVER['PHP_SELF'] ?>?edit=<?php print $users['results']['id'] ?>" title="Edit" class="char_button">&Delta;</a></td>
			<td><?php if ($users['results']['id'] != 1) { ?><a href="<?php print $_SERVER['PHP_SELF'] ?>?delete=<?php print $users['results']['id'] ?>" title="Delete" class="char_button" onclick="delete_user(<?php print $users['results']['id'] ?>, '<?php print $users['results']['name_first'] ?> <?php print $users['results']['name_last'] ?>'); return false;">&times;</a><?php } else { ?>&nbsp;<?php } ?></td>
		</tr>
<?php
		}
?>
	</tbody>
</table>
<?php
		$layout->print_footer();
	}
}
?>
