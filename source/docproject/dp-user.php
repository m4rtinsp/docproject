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
			$id = $_POST['id'];
			$username = addslashes($_POST['username']);
			$email =  addslashes($_POST['email']);
			$password = addslashes($_POST['password']);
			$password = md5($password);

			if ($id)
				$sql = $db->query("UPDATE {$prefix}user SET username='$username', password='$password', email='$email' WHERE id = $id");
			else
				$sql = $db->query("INSERT INTO {$prefix}user (username, password, email) VALUES ('$username', '$password', '$email')");

			$id = $id ? $id : $db->lastInsertId();

			if ($sql)
				json_print(array('status' => 'true', 'user' => array('id' => $id, 'username' => $username, 'email' => $email), 'text' => array('edit' => get_text('edit'), 'delete' => get_text('delete'))));
			else
				json_print(array('status' => 'false'));
		}
		elseif ($_GET['action'] == 'delete') {
			$id = $_GET['id'];

			$sql = $db->query("DELETE FROM {$prefix}user WHERE id = $id");

			if ($sql)
				json_print(array('status' => true));
			else
				json_print(array('status' => false));
		}
		elseif ($_GET['action'] == 'get') {
			$id = $_GET['id'];

			$sql = $db->query("SELECT id, username, email FROM {$prefix}user WHERE id = $id");
			$user = $sql->fetch( PDO::FETCH_OBJ );

			if ($sql && $user)
				json_print(array('status' => true, 'user' => array('id' => $user->id, 'username' => $user->username, 'email' => $user->email)));
			else
				json_print(array('status' => false));
		}
	}
?>