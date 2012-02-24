<?php
	require 'dp-text.php';

	function db()
	{
		$database = new Database;
		return $database->conn();
	}

	if ($_GET['action'] && (isset($_SESSION['userid']) && $_SESSION['user'])) {
		$prefix = DP_DB_PREFIX;
		$db = db();

		if ($_GET['action'] == 'save') {
			$code = $_GET['code'];
			
			if ($code)
				$sql = $db->query("UPDATE {$prefix}config SET theme='$code'");
			

			if ($sql)
				json_print(array('status' => 'true', 'text' => array('theme_set_success' => get_text('theme_set_success'), 'theme_set_false' => get_text('theme_set_false'))));
			else
				json_print(array('status' => 'false'));
		}
	}
?>