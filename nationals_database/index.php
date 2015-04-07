<?php
require_once('settings.php');

$authentication->authenticate();
$layout->print_header();
?>
<h2>Welcome <?php print $_SESSION['name_first'] ?></h2>
<p>Today is <?php print date('l, F jS, Y') ?> (server time is <?php print date('g:i a T') ?>).<br />
	There are currently <?php print $n = mysql_num_rows(mysql_query('SELECT id FROM nationals')) ?> national<?php if ($n != 1) print 's'; ?> registered in the database.</p>
<?php
$layout->print_footer();
?>