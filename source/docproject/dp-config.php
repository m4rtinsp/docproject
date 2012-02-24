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
			$appname = $_POST['appname'];
			$url = substr($_POST['url'], 1);
			$logo = "";

			if (isset($_FILES) && !empty($_FILES['file']) && image_valid($_FILES['file'])) {
				$logo = image_upload($_FILES['file']);
				$logo = $logo ? ", image = '$logo'" : '';
			}

			if (!$logo && isset($_POST['remove_image'])) {
				$sql = $db->query("SELECT image FROM {$prefix}config");
				$config = $sql->fetch( PDO::FETCH_OBJ );
				remove_image($config->image);

				$logo = ", image = ''";
			}

			$sql = $db->query("UPDATE {$prefix}config SET app_name='$appname' $logo");

			if ($sql)
				header("Location: ../$url");
			else
				header("Location: ../$url&error=1");
			
		}
	}

	function image_valid($img)
	{
		if ($img['type'] != 'image/png' && $img['type'] != 'image/jpg' && $img['type'] != 'image/jpeg' && $img['type'] != 'image/gif')
			return false;
		else
			return true;
	}

	function image_upload($img)
	{
		$ext = explode('.', $img['name']);
		$ext = $ext[count($ext)-1];
		$img_name = "logo.$ext";
		$upload = move_uploaded_file($img['tmp_name'], "../public/$img_name");

		if ($upload)
			return $img_name;
		else
			return false;
	}

	function remove_image($name = null)
	{
		@unlink("../public/$name");
	}
?>