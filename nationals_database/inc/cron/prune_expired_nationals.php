<?php
require_once('settings.php');

if (mysql_query('DELETE FROM nationals WHERE validity_end_date !=0 AND validity_end_date < NOW()')) {
	if (mysql_affected_rows() > 0) {
		print mysql_affected_rows() 'expired '.( (mysql_affected_rows() == 1) ? 'record' : 'records').' was pruned from the database.';
	} else {
		print 'No expired records to pruned from the database were found.';
	}
} else {
	print 'Could not prune expired records: '.mysql_error();
}
?>