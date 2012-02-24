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
			$title = addslashes($_POST['title']);
			$alt =  addslashes($_POST['alt']);
			$url =  addslashes($_POST['url']);

			if ($id)
				$sql = $db->query("UPDATE {$prefix}link SET title='$title', alt='$alt', link='$url' WHERE id = $id");
			else
				$sql = $db->query("INSERT INTO {$prefix}link (title, alt, link) VALUES ('$title', '$alt', '$url')");

			$id = $id ? $id : $db->lastInsertId();

			if ($sql)
				json_print(array('status' => 'true', 'link' => array('id' => $id, 'title' => $title, 'alt' => $alt, 'link' => $url), 'text' => array('delete' => get_text('delete'), 'edit' => get_text('edit'))));
			else
				json_print(array('status' => 'false'));
		}
		elseif ($_GET['action'] == 'delete') {
			$id = $_GET['id'];

			$sql = $db->query("DELETE FROM {$prefix}link WHERE id = $id");

			if ($sql)
				json_print(array('status' => true));
			else
				json_print(array('status' => false));
		}
		elseif ($_GET['action'] == 'get') {
			$id = $_GET['id'];

			$sql = $db->query("SELECT * FROM {$prefix}link WHERE id = $id");
			$link = $sql->fetch( PDO::FETCH_OBJ );

			if ($sql && $link)
				json_print(array('status' => true, 'link' => array('id' => $link->id, 'title' => $link->title, 'alt' => $link->alt, 'url' => $link->link)));
			else
				json_print(array('status' => false));
		}
	}
?>