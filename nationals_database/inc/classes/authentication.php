<?php
class authentication {

	function sign_in ($sign_in, $password) {
		global $info;
		$password = sha1($password);
		$authentication['sql'] = 'SELECT id, name_first, name_last, is_admin, can_export, permission_nat, permission_pp, permission_rest, permission_birth_cert FROM users WHERE sign_in = "'.$sign_in.'" AND password = "'.$password.'"';
		if ($authentication['query'] = mysql_query($authentication['sql']) and mysql_num_rows($authentication['query']) == 1) {
			$authentication['results'] = mysql_fetch_assoc($authentication['query']);
			$_SESSION = array();
			$_SESSION = $authentication['results'];
			mysql_query('UPDATE users SET last_visited = NOW(), last_session_id = "'.sha1(session_id()).'" WHERE id = "'.$_SESSION['id'].'" LIMIT 1');
			return true;
		} elseif ($authentication['query'] and mysql_num_rows($authentication['query']) == 0) {
			return false;
		} else {
			die('An authentication error has occured: '.mysql_error());
		}
	}

	function authenticate() {
		global $info;
		$authenticate['sql'] = '	SELECT	id
							FROM		users
							WHERE 	id = "'.$_SESSION['id'].'" AND
									last_session_id = "'.sha1(session_id()).'"
							LIMIT 1';
		$authenticate['query'] = mysql_query($authenticate['sql']);
		$authenticate['num_rows'] = mysql_num_rows($authenticate['query']);
		if ($authenticate['query'] and $authenticate['num_rows'] == 1) { /* User exists. */
			return true;
		} elseif ($authenticate['query'] and $authenticate['num_rows'] == 0) { /* User does not exist. */
			$this->sign_out();
			$_SESSION['referer'] = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
			header('Location: '.SITE_PATH.'/sign.php');
		} else { /* MySQL error. */
			die('An authentication error has occured: '.mysql_error());
		}
	}

	function sign_out () {
		$_SESSION = array();
		session_destroy(); 
		session_start();
	}
	
	function is_admin ($redirect = false) {
		$users['sql'] = "SELECT id FROM users WHERE id = {$_SESSION['id']} AND is_admin = true LIMIT 1" ;
		if ($users['query'] = mysql_query($users['sql']) and mysql_num_rows($users['query']) == 1) {
			return true;
		} else {
			if ($redirect) {
				header('Location: '.SITE_PATH);
			} else {
				return false;
			}
		}
	}

}
?>