<?php
	// Basic settings
	$ROOT = explode('index.php', $_SERVER['SCRIPT_FILENAME']);
	$WEBROOT = explode('index.php', $_SERVER['SCRIPT_NAME']);

	DEFINE('DP_ROOT', $ROOT[0]);
	DEFINE('DP_WEB_ROOT', $WEBROOT[0]);
	
	// Database
	DEFINE('DP_DB_HOST',   'localhost');
	DEFINE('DP_DB_NAME',   'docproject');
	DEFINE('DP_DB_USER',   'root');
	DEFINE('DP_DB_PASS',   '');
	DEFINE('DP_DB_PREFIX', 'dp_');
?>