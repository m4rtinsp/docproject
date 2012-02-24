<?php
	include DP_ROOT . 'docproject/dp-database.php';
	
	function db($access = false, $db = false)
	{
		$database = new Database;

		if ($access && !$db)
			return $database->conn($access);
		elseif ($access && $db)
			return $database->conn($access, $db);
		else
			return $database->conn();
	}

	function dbcheck()
	{
		return db(true);
	}

	function dbcreate()
	{
		try {
			if (db(true) && !db(true, true) && dbcheck() && DP_DB_NAME) {
				$result = db(true)->query("CREATE DATABASE `" . DP_DB_NAME . "` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;");
				return $result;
			}
			elseif (db(true) && db(true, true) && dbcheck() && DP_DB_NAME) {
				return true;
			}

			return false;
		}
		catch (Exception $e) {
			return false;
		}
	}

	function dbtables()
	{
		if (dbcheck() && $db = db()) {
			try {
				$sql = $db->query("SHOW TABLES");
				$tables = $sql->fetchAll( PDO::FETCH_ASSOC );

				if (empty($tables)) {
					$result = $db->query("
						CREATE TABLE IF NOT EXISTS `dp_config` (
						  `app_name` varchar(125) NOT NULL,
						  `image` varchar(255) DEFAULT NULL,
						  `theme` varchar(125) NOT NULL DEFAULT 'default'
						) ENGINE=InnoDB DEFAULT CHARSET=utf8;
					");
					$result = $db->query("INSERT INTO `dp_config` (`app_name`, `image`, `theme`) VALUES ('Doc Project', 'logo.png', 'default');");

					$result = $db->query("
						CREATE TABLE IF NOT EXISTS `dp_language` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `description` varchar(125) NOT NULL,
						  `default` tinyint(4) NOT NULL DEFAULT '0',
						  `code` varchar(45) NOT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;
					");
					$result = $db->query("INSERT INTO `dp_language` (`id`, `description`, `default`, `code`) VALUES (1, 'Português', 1, 'pt-br'),(2, 'English', 0, 'en');");

					$result = $db->query("
						CREATE TABLE IF NOT EXISTS `dp_link` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `title` varchar(255) NOT NULL,
						  `alt` varchar(255) DEFAULT NULL,
						  `link` varchar(255) NOT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;
					");
					$result = $db->query("INSERT INTO `dp_link` (`id`, `title`, `alt`, `link`) VALUES (1, 'Site', 'url site', 'http://www.site.com.br'),(2, 'Blog', 'url blog', 'http://www.blog.com.br');");

					$result = $db->query("
						CREATE TABLE IF NOT EXISTS `dp_topic` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `title` varchar(255) NOT NULL,
						  `text` text,
						  `dp_language_id` int(11) NOT NULL,
						  `dp_version_id` int(11) NOT NULL,
						  `dp_topic_id` int(11) DEFAULT NULL,
						  `principal` tinyint(4) NOT NULL DEFAULT '0',
						  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
						  `position` decimal(10,0) NOT NULL DEFAULT '0',
						  PRIMARY KEY (`id`),
						  KEY `fk_dp_topic_dp_language` (`dp_language_id`),
						  KEY `fk_dp_topic_dp_version1` (`dp_version_id`),
						  KEY `fk_dp_topic_dp_topic1` (`dp_topic_id`)
						) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;
					");

					$result = $db->query("
						CREATE TABLE IF NOT EXISTS `dp_user` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `username` varchar(125) NOT NULL,
						  `password` varchar(255) NOT NULL,
						  `email` varchar(255) NOT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;
					");
					$result = $db->query("INSERT INTO `dp_user` (`id`, `username`, `password`, `email`) VALUES (5, 'administrator', '21232f297a57a5a743894a0e4a801fc3', 'admin@admin.com');");

					$result = $db->query("
						CREATE TABLE IF NOT EXISTS `dp_version` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `description` varchar(45) NOT NULL,
						  `default` tinyint(4) NOT NULL DEFAULT '0',
						  PRIMARY KEY (`id`)
						) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
					");
					$result = $db->query("INSERT INTO `dp_version` (`id`, `description`, `default`) VALUES (1, '1.0', 1);");

					$result = $db->query("
						ALTER TABLE `dp_topic`
						  ADD CONSTRAINT `fk_dp_topic_dp_language` FOREIGN KEY (`dp_language_id`) REFERENCES `dp_language` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
						  ADD CONSTRAINT `fk_dp_topic_dp_topic1` FOREIGN KEY (`dp_topic_id`) REFERENCES `dp_topic` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
						  ADD CONSTRAINT `fk_dp_topic_dp_version1` FOREIGN KEY (`dp_version_id`) REFERENCES `dp_version` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
					");

					return $result;
				}
				else
					return true;
			}
			catch (Exception $e) {
				return false;
			}
		}
	}

	function check_user($check_only = false, $admin = false)
	{
		if (!isset($_SESSION))
			session_start();

		if (isset($_SESSION['userid']) && $_SESSION['user'])
			$_SESSION['current_topic'] = get_current_topic('id');

		if ($check_only) {
			if (isset($_SESSION['userid']) && $_SESSION['user'])
				return true;
			else
				return false;
		}
		else {
			if (isset($_SESSION['userid']) && isset($_SESSION['user']) && $_SESSION['user']) {
				$url = get_current_params(array('post', 'lang', 'version'));
				$link = !$admin ? "<a href='?admin" . get_current_params(array('lang', 'version')) . "'>" . get_text('admin') . "</a>" : "<a href='" . get_url_home() . str_replace('&', '?', $url) . "'>Site</a>";
				$user = is_object($_SESSION['user']) ? $_SESSION['user']->username : $_SESSION['user'];

				return '[ ' . $user . "<a href='" . get_url_home() . "docproject/dp-login.php?action=out$url'>" . get_text('exit') . " ]</a>" . "<a href='" . get_url_home() . "?new_topic=true" . get_current_params(array('lang', 'version')) . "'>" . get_text('add_topic') . "</a>" . $link;
			}
			else
				return '<a href="#" rel="login">' . get_text('login') . '</a>';
		}
	}

	function get_themes()
	{
		$ponteiro  = opendir(getcwd() . '\themes');
		$themes = array();

		while ($item = readdir($ponteiro)) {
			if (strpos($item, '.') === false)
			array_push($themes, $item);
		}

		return $themes;
	}

	function get_all_users()
	{
		$prefix = DP_DB_PREFIX;
		$sql = db()->query("SELECT * FROM {$prefix}user");

		return $sql->fetchAll( PDO::FETCH_OBJ );
	}

	function get_text($field)
	{
		$file = DP_ROOT . 'locale/' . get_current_language('code') . '/site.ini';
		$field = strtoupper($field);

		if (!is_file($file))
			die("File not found. ($file)");

		$ini = parse_ini_file($file);
		return isset($ini[$field]) ? $ini[$field] : 'field not found';
	}

	function get_app_name($logo = false)
	{
		$prefix = DP_DB_PREFIX;
		$sql = db()->query("SELECT * FROM {$prefix}config");
		$config = $sql->fetch( PDO::FETCH_OBJ );
		$appname = $config->app_name;
		$image = $config->image;

		if ($logo && $image)
			$logo = "<img src='public/$image' title='$appname' alt='app logo'/>";
		else
			$logo = $appname;

		return $logo;
	}

	function get_current_theme()
	{
		$prefix = DP_DB_PREFIX;
		$sql = db()->query("SELECT * FROM {$prefix}config");

		return $sql->fetch( PDO::FETCH_OBJ )->theme;
	}

	function get_current_version()
	{
		$prefix = DP_DB_PREFIX;
		$where = isset($_GET['version']) && count(get_version_by('id', $_GET['version'])) == 1 ? "id = '" . $_GET['version'] . "'" : "`default` = 1";
		$sql = db()->query("SELECT * FROM {$prefix}version WHERE $where");

		return $sql->fetch( PDO::FETCH_OBJ );
	}

	function get_version_by($by, $val)
	{
		$prefix = DP_DB_PREFIX;
		$sql = db()->query("SELECT * FROM {$prefix}version WHERE $by = '$val'");

		return $sql->fetchAll( PDO::FETCH_OBJ );
	}

	function get_language_by($by, $val)
	{
		$prefix = DP_DB_PREFIX;
		$sql = db()->query("SELECT * FROM {$prefix}language WHERE $by = '$val'");

		return $sql->fetchAll( PDO::FETCH_OBJ );
	}

	function get_app_logo()
	{
		$prefix = DP_DB_PREFIX;
		$sql = db()->query("SELECT * FROM {$prefix}config");

		return $sql->fetch( PDO::FETCH_OBJ )->image;
	}

	function get_current_doc_version()
	{
		$prefix = DP_DB_PREFIX;
		$sql = db()->query("SELECT * FROM {$prefix}version");
	
		return $sql->fetch( PDO::FETCH_OBJ )->description;
	}

	function get_current_topic($field = null, $textarea = false, $preview = false)
	{
		$prefix = DP_DB_PREFIX;
		$where = isset($_GET['post']) ? " AND id = '" . $_GET['post'] . "'" : ' AND principal = 1';

		$sql = db()->query("SELECT * FROM {$prefix}topic WHERE dp_language_id = " . get_current_language('id') . ' AND dp_version_id = ' . get_current_version()->id . $where);
		$topic = $sql->fetch( PDO::FETCH_OBJ );

		if ($field && $topic) {
			if (!$textarea) {
				if ($field == 'text') {
					$topic->text = replace_line_break($topic->text);
					$topic->text = replace_tag($topic->text);
					$topic->$field = $topic->$field;
				}
			}
			
			if ($preview)
				$topic->$field = "<div class='preview'><h3>Preview</h3><div class='show'>" . $topic->$field . '</div></div>';

			return $topic->$field;
		}

		return $topic;
	}

	function get_topic_by($by, $val, $field = null, $principal = true)
	{
		$prefix = DP_DB_PREFIX;
		$val = $val ? $val != 'NULL' ? "= '$val'" : "is $val" : '= 0';
		$extrawhere = !$principal ? "AND principal <> 1 AND dp_language_id = " . get_current_language('id') : null;

		$sql = db()->query("SELECT * FROM {$prefix}topic WHERE $by $val $extrawhere");
		$topic = $sql->fetchAll( PDO::FETCH_OBJ );

		if ($field && isset($topic->$field))
			return $topic->$field;
		
		return $topic;
	}

	function get_current_language($field = null)
	{
		$prefix = DP_DB_PREFIX;

		$where = isset($_GET['lang']) && count(get_language_by('code', $_GET['lang'])) == 1 ? "code = '" . $_GET['lang'] . "'" : "`default` = 1";
		$sql = db()->query("SELECT * FROM {$prefix}language WHERE $where");
		$language = $sql->fetch( PDO::FETCH_OBJ );

		if ($field && isset($language->$field))
			return $language->$field;

		return $language;
	}

	function get_all_languages()
	{
		$prefix = DP_DB_PREFIX;
		$sql = db()->query("SELECT * FROM {$prefix}language");
		$languages = $sql->fetchAll( PDO::FETCH_OBJ );
		
		foreach ($languages as $key => $language) {
			$url = get_current_params(array('admin', 'version')) ? str_replace('&', '?', get_current_params(array('admin', 'version'))) : '?';
			$e = get_url('admin') && get_url('admin') != '/?' ? '&' : '';

			$languages[$key]->url = $url . $e .'lang=' . $language->code;
		}

		return $languages;
	}

	function get_all_versions()
	{
		$prefix = DP_DB_PREFIX;
		$sql = db()->query("SELECT * FROM {$prefix}version");
		$versions = $sql->fetchAll( PDO::FETCH_OBJ );
		
		foreach ($versions as $key => $version) {
			$url = get_current_params(array('admin', 'lang')) ? str_replace('&', '?', get_current_params(array('admin', 'lang'))) : '?';
			$e = get_url('admin') && get_url('admin') != '/?' && get_url('admin') != '/' ? '&' : '';

			$versions[$key]->url = $url . $e .'version=' . $version->id;
		}

		return $versions;
	}

	function get_all_topics()
	{
		$prefix = DP_DB_PREFIX;
		$sql = db()->query("SELECT * FROM {$prefix}topic WHERE dp_topic_id is NULL AND principal = 0 AND dp_language_id = " . get_current_language('id') . ' AND dp_version_id = ' .  get_current_version()->id . ' ORDER BY position');
		$topics = $sql->fetchAll( PDO::FETCH_OBJ );

		foreach ($topics as $key => $topic) {
			$topics[$key]->url = DP_WEB_ROOT . '?post=' . $topic->id . create_url(array('post', 'new_topic'));
			$topics[$key]->children = get_sub_topics($topic->id);
		}

		return $topics;
	}

	function get_sub_topics($topic_id)
	{
		$prefix = DP_DB_PREFIX;
		$sql = db()->query("SELECT * FROM {$prefix}topic WHERE dp_topic_id = $topic_id ORDER BY position");
		$topics = $sql->fetchAll( PDO::FETCH_OBJ );

		foreach ($topics as $key => $topic) {
			$topics[$key]->url = DP_WEB_ROOT . '?post=' . $topic->id . create_url(array('post', 'new_topic'));
		}

		return $topics;
	}

	function get_breadcrumb($separator)
	{
		$breadcrumb = null;

		if (new_topic())
			return get_text('add_topic');

		$app_name = '<a href=\'' . get_url() . '\'>' . get_app_name() . '</a> ';
		$topic = get_current_topic();
		$parent = $topic ? get_topic_by('id', $topic->dp_topic_id) : null;

		$breadcrumb .= $topic && $topic->principal != 1 ? $app_name : null;

		if ($topic && $topic->dp_topic_id && $parent)
			$breadcrumb .= $separator . ' <a href=\'' . get_url() . '&post=' . $parent[0]->id . '\'>' . $parent[0]->title . '</a> ';

		if ($topic && $topic->principal != 1)
			$breadcrumb .= $separator . ' ' . $topic->title;
		else
			$breadcrumb .= $topic ? $topic->title : '';

		return $breadcrumb;
	}

	function get_theme_dir()
	{
		return DP_WEB_ROOT . 'themes/' . DP_THEME . '/';
	}

	function get_url($param = null)
	{
		$params = $param ? array('lang', $param) : array('lang');
		$url = substr(get_current_params($params), 1);
		$url = $url ? '?' . $url : $url;

		return DP_WEB_ROOT . $url;
	}

	function get_url_home()
	{
		return DP_WEB_ROOT;
	}

	function create_url($param)
	{
		$params = $_GET;
		$uri = '';

		foreach ($params as $field => $value) {
			if (in_array($field, $param))
				unset($params[$field]);
			else
				$uri .= "&$field=$value&";
		}

		return substr($uri, 0, -1);
	}

	function get_current_params($params)
	{
		$url = null;

		foreach ($params as $param) {
			if (isset($_GET[$param])) {
				if (!$_GET[$param])
					$url .= "&$param";
				else
					$url .= "&$param=" . $_GET[$param];
			}
		}

		return $url;
	}

	function get_form($action, $form = null, $buttons = null)
	{
		if (check_user(true) && (get_current_topic() OR new_topic())) {
			if ($action == 'init') {
				if ($form == 'save_topic' && new_topic())
					return "<form action='" . get_url_home() . "docproject/dp-topic.php?action=save&new=true" . get_current_params(array('lang', 'version')) . "' name='formTopic' method='post'>";
				elseif ($form == 'save_topic')
					return "<form action='" . get_url_home() . "docproject/dp-topic.php?action=save" . get_current_params(array('lang', 'version')) . "' name='formTopic' method='post'>";
			}
			elseif ($action == 'end') {
				$end = null;

				$end .= "<input type='hidden' value='" . get_current_language('id') . "' name='topic-language'>";
				$end .= "<input type='hidden' value='" . get_current_version()->id . "' name='topic-version'>";

				foreach ($buttons as $button => $options) {
					$class = isset($options['class']) ? $options['class'] : null;

					if ($button == 'delete' && !new_topic())
						$end .= "&nbsp;<input type='button' class='$class dp-delete-item' id='item-" . get_current_topic('id') . "' value='" . get_text('delete') . "' />";
					if ($button == 'save')
						$end .= "&nbsp;<input type='submit' class='$class' value='" . get_text('save') . "' />";
				}

				$end = $end ? "<p class='ar'>$end</p>" : $end;
				$end .= '</form>';

				return $end;
			}
		}

		return '';
	}

	function new_topic()
	{
		return isset($_GET['new_topic']) ? true : false;
	}

	function get_footer()
	{
		$footer = "<p>" . get_text('footer_powered_by') . " <a href='http://www.twitter.com/m4rtinsp' title='Paulo Martins' rel='external'>Paulo Martins</a> &copy; 2011 - Docproject. </p>";
		$footer .= "<p>" . get_text('footer_original_theme') . " <a href='http://www.twitter.com/m4rtinsp' title='Paulo Martins' rel='external'>Paulo Martins</a></p>";

		return $footer;
	}

	function get_all_links()
	{
		$prefix = DP_DB_PREFIX;
		$sql = db()->query("SELECT * FROM {$prefix}link");

		return $sql->fetchAll( PDO::FETCH_OBJ );
	}

	function replace_line_break($content)
	{
		$content = explode("[code", $content);
		
		foreach ($content as $i => $block) {
			$t = explode("[/code]", $block);

			if (count($t) > 1) {
				$content[$i] = str_replace(array("<br>","\r\n{"), array("\r\n","{"), $t[0]);
				$content[$i] .= "[/code]" . $t[1];
			}
		}

		return join("[code", $content);
	}

	function replace_tag($content)
	{
		$code = array(
				"]\r\n",
				"[code php]",
				"[code js]",
				"[code css]",
				"[code sql]",
				"[code plain]",
				"[code java]",
				"[code c#]",
				"[code c]",
				"[code delphi]",
				"[code as3]",
				"[code perl]",
				"[code python]",
				"[code ruby]",
				"[code vb]",
				"[code xml]",
				"[code xhtml]",
				"[code html]",
				"[code xslt]",
				"[/code]<br>",
				"[/code]"
			);

		$tag = array(
				"]",
				"<pre class=\"brush: php;\">",
				"<pre class=\"brush: js;\">",
				"<pre class=\"brush: css;\">",
				"<pre class=\"brush: sql;\">",
				"<pre class=\"brush: plain;\">",
				"<pre class=\"brush: java;\">",
				"<pre class=\"brush: c#;\">",
				"<pre class=\"brush: c;\">",
				"<pre class=\"brush: delphi;\">",
				"<pre class=\"brush: as3;\">",
				"<pre class=\"brush: perl;\">",
				"<pre class=\"brush: python;\">",
				"<pre class=\"brush: ruby;\">",
				"<pre class=\"brush: vb;\">",
				"<pre class=\"brush: xml;\">",
				"<pre class=\"brush: xhtml;\">",
				"<pre class=\"brush: html;\">",
				"<pre class=\"brush: xslt;\">",
				"</pre>",
				"</pre>"
			);

		return str_replace($code, $tag, $content);
	}
?>