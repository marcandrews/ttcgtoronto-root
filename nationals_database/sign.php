<?php
require_once('settings.php');

if (isset($_GET['out'])) {
	$authentication->sign_out();
	header('Location: '.SITE_PATH);
}

if ($_POST) {
	if ($authentication->sign_in($_POST['sign_in'], $_POST['password'])) {
		header('Location: '.SITE_PATH);
	} else {
		print 'Sign in failed';
	}
}

$layout->set_is_sign_in_page(true);
$layout->print_header();
?>
<form id="form_sign_in" method="post" action="<?php print $_SERVER['PHP_SELF'] ?>">
	<fieldset>
		<fieldset>
			<ul>
				<li> <label for="sign_in">Sign in</label><input name="sign_in" id="sign_in" type="text" /> </li>
				<li> <label for="password">Password</label><input name="password" id="password" type="password" /> </li>
			</ul>
		</fieldset>
		<fieldset class="controls">
			<input type="submit" value="Sign in" />
		</fieldset>
	</fieldset>
</form>
<?php
$layout->print_footer();
?>
