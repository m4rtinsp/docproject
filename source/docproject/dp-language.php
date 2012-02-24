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
			$code =  addslashes($_POST['code']);

			if ($id)
				$sql = $db->query("UPDATE {$prefix}language SET description='$description', code='$code' WHERE id = $id");
			else {
				$sql = $db->query("INSERT INTO {$prefix}language (description, code) VALUES ('$description', '$code')");

				$dir = "../locale/$code";
				mkdir($dir);
				$fp = fopen("$dir/site.ini", "a");
				fclose($fp);
			}

			$id = $id ? $id : $db->lastInsertId();

			if ($sql)
				json_print(array('status' => 'true', 'language' => array('id' => $id, 'description' => $description, 'code' => $code), 'text' => array('admin_button_set_as_default' => get_text('
				admin_button_set_as_default'), 'delete' => get_text('delete'), 'edit' => get_text('edit'))));
			else
				json_print(array('status' => 'false'));
		}
		elseif ($_GET['action'] == 'delete') {
			$id = $_GET['id'];

			// Pega a quantidade de idiomas cadastrados
			$languages = $db->query("SELECT COUNT(*) as n FROM {$prefix}language");
			$n = $languages->fetch( PDO::FETCH_OBJ )->n;

			// retorna falso caso a resposta seja 1, pois é necessário pelo menos 1 registro de idioma
			if ($n == 1) {
				json_print(array('status' => false, 'message' => get_text('alert_one_language')));
				exit;
			}

			// Remove o registro e sua respectiva pasta em 'locale'
			$sql = $db->query("SELECT code FROM {$prefix}language WHERE id = $id");
			$code = $sql->fetch( PDO::FETCH_OBJ );

			if (isset($code->code)) {
				$sql = $db->query("DELETE FROM {$prefix}language WHERE id = $id");
				@unlink("../locale/$code->code/site.ini");
				@rmdir("../locale/$code->code");
			}

			// Verifica a quantidade de registros após a remoção do item anterior
			$languages = $db->query("SELECT id FROM {$prefix}language");
			$languages = $languages->fetchAll( PDO::FETCH_OBJ );

			// Se a resposta for 1, seta como principal
			if (count($languages) == 1) {
				$sql = $db->query("UPDATE {$prefix}language SET `default`=1 WHERE id = {$languages[0]->id}");
			}

			if ($sql)
				json_print(array('status' => true));
			else
				json_print(array('status' => false));
		}
		elseif ($_GET['action'] == 'get') {
			$id = $_GET['id'];

			$sql = $db->query("SELECT id, description, code FROM {$prefix}language WHERE id = $id");
			$language = $sql->fetch( PDO::FETCH_OBJ );

			if ($sql && $language)
				json_print(array('status' => true, 'language' => array('id' => $language->id, 'description' => $language->description, 'code' => $language->code)));
			else
				json_print(array('status' => false));
		}
		elseif ($_GET['action'] == 'set_default') {
			$id = $_GET['id'];

			$sql = $db->query("UPDATE {$prefix}language SET `default`=0");
			$sql = $db->query("UPDATE {$prefix}language SET `default`=1 WHERE id = $id");

			if ($sql)
				json_print(array('status' => true, 'text' => array('admin_button_is_default' => get_text('admin_button_is_default'), 'admin_button_set_as_default' => get_text('admin_button_set_as_default'))));
			else
				json_print(array('status' => false));
		}
	}
?>