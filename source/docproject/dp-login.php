<?php
	session_start();

	require '../config.php';
	require 'dp-database.php';

	if (isset($_GET['action']) && $_GET['action'] == 'out') {
		unset($_SESSION['userid']);
		unset($_SESSION['user']);
		session_destroy();

		$url = null;

		if (isset($_GET['post']) && $_GET['post'])
			$url .= '?post=' . $_GET['post'];
		if (isset($_GET['lang']) && $_GET['lang']) {
			$url = $url ? $url . '&' : '?';
			$url .= 'lang=' . $_GET['lang'];
		}
		if (isset($_GET['version']) && $_GET['version']) {
			$url = $url ? $url . '&' : '?';
			$url .= 'version=' . $_GET['version'];
		}
		
		header('Location: ../' . $url);
		exit;
	}

	if (!$_POST OR !$_POST['username'] OR !$_POST['password']) {
		header('Location: ../');
		exit;
	}

	login($_POST['username'], $_POST['password']);

	function db()
	{
		$database = new Database;
		return $database->conn();
	}

	function login($username, $password)
	{
		$prefix = DP_DB_PREFIX;

		// Clear fields
		$username = addslashes($_POST['username']);
		$password = addslashes($_POST['password']);
		$password = md5($password);
		
		$sql = db()->prepare("SELECT id, username FROM {$prefix}user WHERE username = ? AND password = ?");
		$sql->execute(array($username, $password));
		$result = $sql->fetch( PDO::FETCH_OBJ );
		
		if (!empty($result)) {
			$url = null;

			if (isset($_GET['post']) && $_GET['post'])
				$url .= '?post=' . $_GET['post'];
			if (isset($_GET['lang']) && $_GET['lang']) {
				$url = $url ? $url . '&' : '?';
				$url .= 'lang=' . $_GET['lang'];
			}
			if (isset($_GET['version']) && $_GET['version']) {
				$url = $url ? $url . '&' : '?';
				$url .= 'version=' . $_GET['version'];
			}

			$_SESSION['userid'] = md5($result->id);
			$_SESSION['user'] = $result->username;
		}

		header('Location: ../' . $url);
	}
?>