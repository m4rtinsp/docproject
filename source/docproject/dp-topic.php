<?php
	session_start();

	require '../config.php';
	require 'dp-database.php';

	function db()
	{
		$database = new Database;
		return $database->conn();
	}

	if ($_GET['action']) {
		$prefix = DP_DB_PREFIX;
		$db = db();

		if ($_GET['action'] == 'save' && (isset($_SESSION['userid']) && $_SESSION['user'])) {
			$url = null;
			$id = isset($_SESSION['current_topic']) && $_SESSION['current_topic'] && !isset($_GET['new']) ? $_SESSION['current_topic'] : false;
			$title = $_POST['topic-title'];
			$text = str_replace(array("\""), array("'"), $_POST['topic-text']);
			$language = $_POST['topic-language'];
			$version = $_POST['topic-version'];
			$parent_topic = $_POST['topic-parent-id'] ? $_POST['topic-parent-id'] : 'NULL';
			$principal = $_POST['topic-principal'];

			if ((int)$principal == 1 && $parent_topic == 'NULL')
				$db->query("UPDATE {$prefix}topic SET principal = 0 WHERE 0");
			else
				$principal = 0;

			if ($id)
				$sql = $db->query("UPDATE {$prefix}topic SET title = \"$title\", text = \"$text\", dp_topic_id = $parent_topic, principal = $principal WHERE id = $id");
			else
				$sql = $db->query("INSERT INTO {$prefix}topic (title, text, dp_language_id, dp_version_id, dp_topic_id, principal) VALUES ('$title', '$text', $language, $version, $parent_topic, $principal)");

			if (isset($_GET['lang']) && $_GET['lang'])
				$url .= '&lang=' . $_GET['lang'];
			if (isset($_GET['version']) && $_GET['version'])
				$url .= '&version=' . $_GET['version'];

			$id = $id ? $id : $db->lastInsertId();

			header('Location: ../?post=' . $id . $url);
		}
		elseif ($_GET['action'] == 'delete' && (isset($_SESSION['userid']) && $_SESSION['user'])) {
			$id = isset($_GET['post']) && $_GET['post'] ? $_GET['post'] : null;
			$url = null;

			if ($id) {
				try {
					$sql = db()->query("DELETE FROM {$prefix}topic WHERE dp_topic_id = $id");
					$sql = db()->query("DELETE FROM {$prefix}topic WHERE id = $id");
				}
				catch( Exception $e ) {

				}
					
				if (isset($_GET['lang']) && $_GET['lang'])
					$url .= '?lang=' . $_GET['lang'];
				if (isset($_GET['version']) && $_GET['version'])
					$url .= $url ? '&version=' . $_GET['version'] : '?version=' . $_GET['version'];

				header('Location: ../' . $url);
			}
		}
		elseif ($_GET['action'] == 'positions' && (isset($_SESSION['userid']) && $_SESSION['user'])) {
			if (isset($_POST['ids']) && isset($_POST['pos'])) {
				$ids = $_POST['ids'];
				$pos = $_POST['pos'];

				foreach ($ids as $key => $id) {
					$sql = $db->query("UPDATE {$prefix}topic SET position = '{$pos[$key]}' WHERE id = $id");
				}

				if ($sql)
					echo json_encode(true);
				else
					echo json_encode(false);
			}
		}
		else
			header('Location: ../');
	}
?>