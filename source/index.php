<?php
	session_start();

	include 'config.php';
	include 'preferences.php';
	include DP_ROOT . 'docproject/dp-data.php';

	if (!db(true) OR is_dir('install')) {
		include DP_ROOT . 'install/index.php';
		exit;
	}

	if (isset($_GET['admin'])) {
		if (isset($_SESSION['userid']) && $_SESSION['user'])
			include DP_ROOT . 'dp-admin/index.php';
		else
			header('Location: /');
	}
	else
		include DP_ROOT . 'themes/' . DP_THEME . '/index.php';
?>