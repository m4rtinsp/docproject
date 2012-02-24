<?php
	session_start();

	require '../config.php';
	require 'dp-database.php';

	if (isset($_GET['get_text']) && $_GET['get_text']) {
		json_print(array('status' => true, 'text' => get_text($_GET['get_text'])) );
		exit;
	}

	function get_current_language($field = null)
	{
		$prefix = DP_DB_PREFIX;
		$where = isset($_GET['lang']) ? "code = '" . $_GET['lang'] . "'" : "`default` = 1";
		$sql = db()->query("SELECT * FROM {$prefix}language WHERE $where");
		$language = $sql->fetch( PDO::FETCH_OBJ );

		if ($field && isset($language->$field))
			return $language->$field;

		return $language;
	}

	function get_text($field)
	{
		$file = '../locale/' . get_current_language('code') . '/site.ini';
		$field = strtoupper($field);

		if (!is_file($file))
			die("File not found. ($file)");

		$ini = parse_ini_file($file);
		return isset($ini[$field]) ? $ini[$field] : 'field not found';
	}

	function json_print($response)
	{
		header("Content-type: application/json");
		echo json_encode($response);
	}
?>