<?php
define('SQL_HOST',		'localhost');
define('SQL_DB',		'ttcgtoro_nationals');
define('SQL_SIGN_IN',	'ttcgtoro_nat');
define('SQL_PASSWORD',	'');
define('EMAIL',		'');
define('SITE_URL',		'http://'.$_SERVER['HTTP_HOST']);
define('SITE_PATH',		'/nationals_database');
define('SITE_ROOT',		$_SERVER['DOCUMENT_ROOT']);

define('REG_EX_POSTAL_CODE',	'/^([abceghjklmnprstvxyABCEGHJKLMNPRSTVXY][0-9][abceghjklmnprstvwxyzABCEGHJKLMNPRSTVWXYZ]) {0,1}([0-9][abceghjklmnprstvwxyzABCEGHJKLMNPRSTVWXYZ][0-9])$/');
define('REG_EX_TEL',		'/^[\(]{1}([0-9]{3})[\)]{1}[ ]{1}([0-9]{3})[\-]{1}([0-9]{4})$/');
define('REG_EX_EMAIL',		'/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/');
define('REG_EX_DATE',		'/^((((19|20)(([02468][048])|([13579][26]))-02-29))|((20[0-9][0-9])|(19[0-9][0-9]))-((((0[1-9])|(1[0-2]))-((0[1-9])|(1\d)|(2[0-8])))|((((0[13578])|(1[02]))-31)|(((0[1,3-9])|(1[0-2]))-(29|30)))))$/');

define('DATE_FORMAT_S',	'Y/m/d');
define('DATE_FORMAT_M',	'Y/m/d @ H:i');
define('DATE_FORMAT_L',	'l, F dS, Y, H:i');

$provinces_territories = array (
							array('AB', 'Alberta'),
							array('BC', 'British Columbia'),
							array('MB', 'Manitoba'),
							array('NB', 'New Brunswick'),
							array('NL', 'Newfoundland &amp; Labrador'),
							array('NS', 'Nova Scotia'),
							array('NT', 'Northwest Territories'),
							array('NU', 'Nunavut'),
							array('ON', 'Ontario'),
							array('PE', 'Prince Edward Island'),
							array('QC', 'Quebec'),
							array('SK', 'Saskatchewan'),
							array('YK', 'Yukon')
						 );

if (mysql_connect(SQL_HOST, SQL_SIGN_IN, SQL_PASSWORD)) {
	mysql_select_db(SQL_DB);
} else {
	die('The Nationals Database is unavailable: '.mysql_error());
}

function __autoload($class_name) {
	require_once($class_name.'.php');
	if (method_exists($class_name,'init'))
		call_user_func(array($class_name,'init'));
	return true;
}

$authentication = new authentication();
$layout = new layout();
?>