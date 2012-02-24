<?php
	if ($_SERVER['REQUEST_URI'] == '/dp-admin/')
		header('Location: /');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="Content-Language" content="pt-br" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="cache-control" content="no-cache" />
	<meta name="description" content="Doc Project – Software de documentação de projetos" />
	<meta name="keywords" content="Doc Project" />
	<meta name="robots" content="index,follow" />
	<meta name="robots" content="noodp" />
	<meta name="robots" content="noarchive" />
	<meta name="copyright" content="Copyright &copy; Doc Project. Todos os direitos reservados." />
	<meta name="author" content="Paulo Martins" />

	<title>Docproject - <?= get_app_name() ?></title>
	<link rel="stylesheet" href="dp-admin/css/reset.css" type="text/css" />
	<link rel="stylesheet" href="dp-admin/css/docproject.css" type="text/css" />
</head>
<body>
	<div id="container">
		<div id="top-bar">
			<div class="left">
				<h1><a href='<?= get_url() ?>'><?= get_app_name(true) ?></a></h1>
			</div>
			<div class="right">
				<?= check_user(false, true) ?>
				<a href="#" rel="language" class="language"><?= get_current_language('description') ?></a>

				<!-- Modal -->
				<div id="modal-login">
					<form action="<?= get_url_home() ?>docproject/dp-login.php" method="post" id="formLogin">
						<fieldset>
							<input type="text" name="username" class="text medium" value="user" />
							<input type="password" name="password" class="text medium" value="password" />
							<input type="submit" class="button" value="SEND" />
						</fieldset>
					</form>
				</div>
				<div id="modal-language" class="<?= check_user(true) ? 'in' : 'out' ?>">
					<ul>
						<?php foreach (get_all_languages() as $language) { ?>
						<li><a href='<?= $language->url ?>'><?= $language->description ?></a></li>
						<?php } ?>
					</ul>
				</div>
			</div>
		</div>

		<div id="side-bar-top">
			<div><a href="#" rel="versions-slider">Versão: </a> <?= get_current_version()->description ?></div>
			<ul id="list-versions" class="clearfix">
				<?php foreach (get_all_versions() as $version) { ?>
				<li><a href="<?= $version->url ?>"><?= $version->description ?></a></li>
				<?php } ?>
			</ul>
		</div>

		<div id="side-bar">
			<ul id="tabs" class="clearfix">
				<li><a href="?admin<?= get_current_params(array('lang')) ?>&tab=topics" <?php if (!isset($_GET['tab']) OR $_GET['tab'] == 'topics') { ?>class="on"<?php } ?>><?= get_text('admin_label_topics') ?></a></li>
				<li><a href="?admin<?= get_current_params(array('lang')) ?>&tab=users" <?php if (isset($_GET['tab']) && $_GET['tab'] == 'users') { ?>class="on"<?php } ?>><?= get_text('admin_label_users') ?></a></li>
				<li><a href="?admin<?= get_current_params(array('lang')) ?>&tab=themes" <?php if (isset($_GET['tab']) && $_GET['tab'] == 'themes') { ?>class="on"<?php } ?>><?= get_text('admin_label_themes') ?></a></li>
				<li><a href="?admin<?= get_current_params(array('lang')) ?>&tab=languages" <?php if (isset($_GET['tab']) && $_GET['tab'] == 'languages') { ?>class="on"<?php } ?>><?= get_text('admin_label_languages') ?></a></li>
				<li><a href="?admin<?= get_current_params(array('lang')) ?>&tab=versions" <?php if (isset($_GET['tab']) && $_GET['tab'] == 'versions') { ?>class="on"<?php } ?>><?= get_text('admin_label_versions') ?></a></li>
				<li><a href="?admin<?= get_current_params(array('lang')) ?>&tab=links" <?php if (isset($_GET['tab']) && $_GET['tab'] == 'links') { ?>class="on"<?php } ?>><?= get_text('admin_label_links') ?></a></li>
				<li><a href="?admin<?= get_current_params(array('lang')) ?>&tab=config" <?php if (isset($_GET['tab']) && $_GET['tab'] == 'config') { ?>class="on"<?php } ?>><?= get_text('admin_label_config') ?></a></li>
			</ul>
		</div>
		<div id="content">
			<?php if (!isset($_GET['tab']) OR $_GET['tab'] == 'topics') { ?>
			<div id="tab-topics">
				<?php if (get_all_topics()) { ?>
				<a href="#" class="button" rel="save-positions"><?= get_text('admin_form_button_save_position') ?></a>
				<br/><br/><br/>
				<?php } else { ?>
				<?= get_text('label_no_topic_found') ?>
				<?php } ?>

				<?php foreach (get_all_topics() as $key => $topic) { ?>
				<p><input type="text" id="<?= $topic->id ?>" value="<?= $topic->position ?>" class="min text" /> <?= $topic->title ?></p>
					<?php foreach ($topic->children as $key2 => $subtopic) { ?>
						<p class="children"><input type="text" id="<?= $subtopic->id ?>" value="<?= $subtopic->position ?>" class="min text" /> <?= $subtopic->title ?></p>
					<?php } ?>
				<?php } ?>
			</div>
			<?php } elseif (isset($_GET['tab']) && $_GET['tab'] == 'users') { ?>
			<div id="tab-users">
				<form action="" method="post" id="userForm">
					<p>
						<label><?= get_text('admin_form_label_user') ?></label>
						<input type="text" name="user" class="text medium" />
					</p>
					<p>
						<label><?= get_text('admin_form_label_email') ?></label>
						<input type="text" name="email" class="text medium" />
					</p>	
					<p>
						<label><?= get_text('admin_form_label_pass') ?></label>
						<input type="hidden" name="id" value="" />
						<input type="password" name="password" class="text medium" />
						<input type="submit" class="button" value="<?= get_text('save') ?>" />
					</p>
				</form>

				<ul id="list-users">
					<?php foreach (get_all_users() as $user) { ?>
					<li id="item<?= $user->id ?>">
						<label><strong><?= $user->username ?></strong> - <?= $user->email ?></label>
						<a href="#" rel="edit-user" id="id<?= $user->id ?>">[ <?= get_text('edit') ?> ]</a>
						<a href="#" rel="delete-user" id="id<?= $user->id ?>">[ <?= get_text('delete') ?> ]</a>
					</li>
					<?php } ?>
				</ul>
			</div>
			<?php } elseif (isset($_GET['tab']) && $_GET['tab'] == 'themes') { ?>
			<div id="tab-themes">
				<?php foreach (get_themes() as $theme) { ?>
				<ul>
					<li>
						<label><input type='radio' name='theme' class='set-theme' value='<?= $theme ?>' <?php if (get_current_theme() == $theme) { ?>checked<?php } ?> /> <?= $theme ?></label>
					</li>
				</ul>
				<?php } ?>
			</div>
			<?php } elseif (isset($_GET['tab']) && $_GET['tab'] == 'languages') { ?>
			<div id="tab-languages">
				<form action="" method="post" id="userLanguage">
					<p>
						<label><?= get_text('admin_form_label_language') ?></label>
						<input type="text" name="description" class="text medium" />
					</p>
					<p>
						<label><?= get_text('admin_form_label_code') ?></label>
						<input type="text" name="code" class="text medium" />
						<input type="hidden" name="id" value="" />
						<input type="submit" class="button" value="<?= get_text('save') ?>" />
					</p>
				</form>
				<ul id="list-languages">
					<?php foreach (get_all_languages() as $language) { ?>
					<li id="item<?= $language->id ?>">
						<label><strong><?= $language->description ?></strong> - <?= $language->code ?></label>
						<a href="#" rel="edit-language" id="id<?= $language->id ?>">[ <?= get_text('edit') ?> ]</a>
						<a href="#" rel="delete-language" id="id<?= $language->id ?>">[ <?= get_text('delete') ?> ]</a>
						<a href="#" rel="set-language" id="id<?= $language->id ?>"><?php if ($language->default == 1) { ?>- <?= get_text('admin_button_is_default') ?><?php } else { ?>[ <?= get_text('admin_button_set_as_default') ?> ]<?php } ?></a>
					</li>
					<?php } ?>
				</ul>
			</div>

			<?php } elseif (isset($_GET['tab']) && $_GET['tab'] == 'versions') { ?>
			<div id="tab-versions">
				<form action="" method="post" id="userVersions">
					<p>
						<label><?= get_text('admin_form_label_version_name') ?></label>
						<input type="text" name="name-version" class="text medium" />
						<input type="hidden" name="id" value="" />
						<input type="submit" class="button" value="<?= get_text('save') ?>" />
					</p>
				</form>
				<ul id="list-versions-site">
					<?php foreach (get_all_versions() as $version) { ?>
					<li id="item<?= $version->id ?>">
						<label><?= $version->description ?></label>
						<a href="#" rel="edit-version" id="id<?= $version->id ?>">[ <?= get_text('edit') ?> ]</a>
						<a href="#" rel="delete-version" id="id<?= $version->id ?>">[ <?= get_text('delete') ?> ]</a>
						<a href="#" rel="set-version" id="id<?= $version->id ?>"><?php if ($version->default == 1) { ?>- <?= get_text('admin_button_is_default') ?><?php } else { ?>[ <?= get_text('admin_button_set_as_default') ?> ]<?php } ?></a>
					</li>
					<?php } ?>
				</ul>
			</div>

			<?php } elseif (isset($_GET['tab']) && $_GET['tab'] == 'links') { ?>
			<div id="tab-languages">
				<form action="" method="post" id="userLinks">
					<p>
						<label><?= get_text('admin_form_label_title') ?></label>
						<input type="text" name="title" class="text medium" />
					</p>
					<p>
						<label><?= get_text('admin_form_label_alt') ?></label>
						<input type="text" name="alt" class="text medium" />
					</p>
					<p>
						<label><?= get_text('admin_form_label_url') ?></label>
						<input type="text" name="url" class="text medium" />
					
						<input type="hidden" name="id" value="" />
						<input type="submit" class="button" value="<?= get_text('save') ?>" />
					</p>
				</form>
				<ul id="list-links">
					<?php foreach (get_all_links() as $link) { ?>
					<li id="item<?= $link->id ?>">
						<label><strong><?= $link->title ?></strong> - <?= $link->alt ?> - <?= $link->link ?></label>
						<a href="#" rel="edit-link" id="id<?= $link->id ?>">[ <?= get_text('edit') ?> ]</a>
						<a href="#" rel="delete-link" id="id<?= $link->id ?>">[ <?= get_text('delete') ?> ]</a>
					</li>
					<?php } ?>
				</ul>
			</div>

			<?php } elseif (isset($_GET['tab']) && $_GET['tab'] == 'config') { ?>
			<div id="tab-languages">
				<form action="../docproject/dp-config.php?action=save" method="post" id="formConfig" enctype="multipart/form-data">
					<p>
						<label></label>
						<?php if (get_app_logo()) { echo "<img src='../public/" . get_app_logo() . "' />"; } ?>
					</p>
					<p>
						<label><?= get_text('admin_form_label_appname') ?></label>
						<input type="text" name="appname" class="text medium" value="<?= get_app_name(); ?>" />
					</p>
					<p>
						<label><?= get_text('admin_form_label_logo') ?></label>
						<input type="file" name="file" />
					</p>
					<p>
						<label></label>
						<input type="checkbox" name="remove_image" value="1" /> Remover imagem atual
					</p>
					<p>
						<br/>
						<input type="hidden" name="url" value="<?= $_SERVER['REQUEST_URI'] ?>" />
						<input type="submit" class="button" value="<?= get_text('save') ?>" />
					</p>
				</form>
			</div>
			<?php } ?>
		</div>
		<div id="footer">
			<p>Powered by <a href='http://www.twitter.com/m4rtinsp' title='Paulo Martins' rel='external'>m4rtinsp</a> &copy; 2011 - Doc Project. </p>
			<p>Original theme and concept by <a href='http://www.twitter.com/m4rtinsp' title='Paulo Martins' rel='external'>m4rtinsp</a></p>
		</div>
	</div>

<script type="text/javascript" language="javascript" src="dp-admin/js/lib/jquery-1.6.2.min.js"></script>
<script type="text/javascript" language="javascript" src="dp-admin/js/docproject.js"></script>
<script type="text/javascript" language="javascript">
Docproject.text.confirm_remove_item = '<?= get_text('confirm_remove_item') ?>';
Docproject.text.confirm_remove_item_version = '<?= get_text('confirm_remove_item_version') ?>';
Docproject.text.confirm_remove_item_language = '<?= get_text('confirm_remove_item_language') ?>';
Docproject.text.alert_updated_positions = '<?= get_text('alert_updated_positions') ?>';
Docproject.text.alert_updated_positions_false = '<?= get_text('alert_updated_positions_false') ?>';
Docproject.text.alert_fill_fields = '<?= get_text('alert_fill_fields') ?>';
Docproject.text.admin_button_set_as_default = '<?= get_text('admin_button_set_as_default') ?>';
</script>
</body>
</html>