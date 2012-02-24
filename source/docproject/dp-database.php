<?php
	class Database
	{
		public $pdo;

		public function conn($access = false, $db = false)
		{
			if ($access) {
				try {
					if (!DP_DB_HOST)
						return false;

					if ($db)
						@$pdo_check = new PDO("mysql:host=".DP_DB_HOST.";dbname=".DP_DB_NAME."", DP_DB_USER, DP_DB_PASS);
					else
						@$pdo_check = new PDO("mysql:host=".DP_DB_HOST, DP_DB_USER, DP_DB_PASS);

					return $pdo_check;
				}
				catch (Exception $e) {
					return false;
				}
			}
			else {
				if (!$this->pdo) {
					try {
						$this->pdo = new PDO("mysql:host=".DP_DB_HOST.";dbname=".DP_DB_NAME."", DP_DB_USER, DP_DB_PASS);
						$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						$this->pdo->query("SET character_set_results=utf8");
						$this->pdo->query("SET character_set_client=utf8");
					}
					catch (Exception $e) {
						return false;
					}
					
				}
			}

			return $this->pdo;
		}
	}
?>