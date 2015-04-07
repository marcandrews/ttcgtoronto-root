<?php

class layout {
	
	var $title = 'Nationals Database';
	var $is_sign_in_page = false;

	function set_title ($input) {
		$this->title = 'Nationals Database : '.$input;
	}

	function set_is_sign_in_page ($input) {
		if ($input === true) {
			$this->is_sign_in_page = true;
		} else {
			$this->is_sign_in_page = false;
		}
	}

	function print_header () {
		ob_start('ob_gzhandler');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>The Consulate General for the Republic of Trinidad &amp; Tobago <?php print $this->title ?></title>
<link href="css/reset.css" rel="stylesheet" type="text/css" />
<link href="css/screen.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.ui.js"></script>
<script type="text/javascript" src="js/jquery.impromptu.js"></script>
<link href="js/jquery.impromptu.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="js/jquery.form.js"></script>
<script type="text/javascript" src="js/jquery.maskedinput.js"></script>
<script type="text/javascript" src="js/default.js"></script>
</head>
<body>
<div id="wrapper"<?php if ($this->is_sign_in_page) { ?> class="sign_in"<?php } ?>>
	<h1>Nationals Database</h1>
<?php
		if ($this->is_sign_in_page == false) {
?>
	<ul id="navigation" title="Navigation">
		<li><a href="<?php print SITE_PATH ?>">Home</a></li>
		<li><a href="<?php print SITE_PATH ?>/database.php">Search</a></li>
<?php
			if ($_SESSION['permission_nat'] > 0) {
?>
		<li><a href="<?php print SITE_PATH ?>/database.php?add">Add</a></li>
<?php
			}
			if ($_SESSION['can_export']) {
?>
		<li><a href="<?php print SITE_PATH ?>/export.php">Export</a></li>
<?php
			}
?>
		<li class="right"><a href="<?php print SITE_PATH ?>/sign.php?out" id="navigation_sign_out">Sign Out</a></li>
<?php
			if ($_SESSION['is_admin']) {
?>
		<li class="right"><a href="<?php print SITE_PATH ?>/users.php">Users</a>
			<ul>
				<li><a href="<?php print SITE_PATH ?>/users.php?add">Add a User</a></li>
			</ul>
		</li>
<?php
			}
?>
	</ul>
<?php
		}
?>
<!-- Start page contents -->


<?php
	}

	function print_footer () {
?>


<!-- End page contents -->
</div>
<div id="footer">
	<div id="standards">
		<a href="http://validator.w3.org/check/referer"><abbr title="Extensible HyperText Markup Language">XHTML</abbr> 1.1</a>
		|
		<a href="http://jigsaw.w3.org/css-validator/check/referer"><abbr title="Cascading Style Sheets">CSS</abbr> 3.0</a>
	</div>
</div>
</body>
</html>
<?php
		ob_end_flush();
	}	
}

?>