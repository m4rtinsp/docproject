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
			$description = addslashes($_POST['description']);
			
			if ($id)
				$sql = $db->query("UPDATE {$prefix}version SET description='$description' WHERE id = $id");
			else
				$sql = $db->query("INSERT INTO {$prefix}version (description) VALUES ('$description')");

			$id = $id ? $id : $db->lastInsertId();

			if ($sql)
				json_print(array('status' => 'true', 'version' => array('id' => $id, 'description' => $description), 'text' => array('admin_button_set_as_default' => get_text('
				admin_button_set_as_default'), 'edit' => get_text('edit'), 'delete' => get_text('delete'))));
			else
				json_print(array('status' => 'false'));
		}
		elseif ($_GET['action'] == 'delete') {
			$id = $_GET['id'];

			// Pega a quantidade de versões cadastradas
			$versions = $db->query("SELECT COUNT(*) as n FROM {$prefix}version");
			$n = $versions->fetch( PDO::FETCH_OBJ )->n;

			// retorna falso caso a resposta seja 1, pois é necessário pelo menos 1 registro de versão
			if ($n == 1) {
				json_print(array('status' => false, 'message' => get_text('alert_one_version')));
				exit;
			}

			// Remove o registro
			$sql = $db->query("DELETE FROM {$prefix}version WHERE id = $id");

			// Verifica a quantidade de registros após a remoção do item anterior
			$versions = $db->query("SELECT id FROM {$prefix}version");
			$versions = $versions->fetchAll( PDO::FETCH_OBJ );

			// Se a resposta for 1, seta como principal
			if (count($versions) == 1) {
				$sql = $db->query("UPDATE {$prefix}version SET `default`=1 WHERE id = {$versions[0]->id}");
			}

			if ($sql)
				json_print(array('status' => true));
			else
				json_print(array('status' => false));
		}
		elseif ($_GET['action'] == 'get') {
			$id = $_GET['id'];

			$sql = $db->query("SELECT id, description FROM {$prefix}version WHERE id = $id");
			$version = $sql->fetch( PDO::FETCH_OBJ );

			if ($sql && $version)
				json_print(array('status' => true, 'version' => array('id' => $version->id, 'description' => $version->description)));
			else
				json_print(array('status' => false));
		}
		elseif ($_GET['action'] == 'set_default') {
			$id = $_GET['id'];

			$sql = $db->query("UPDATE {$prefix}version SET `default`=0");
			$sql = $db->query("UPDATE {$prefix}version SET `default`=1 WHERE id = $id");

			if ($sql)
				json_print(array('status' => true, 'text' => array('admin_button_is_default' => get_text('admin_button_is_default'), 'admin_button_set_as_default' => get_text('admin_button_set_as_default'))));
			else
				json_print(array('status' => false));
		}
	}
?>