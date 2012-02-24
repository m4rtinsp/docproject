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
	<link rel="stylesheet" href="<?= get_theme_dir() ?>css/reset.css" type="text/css" />
	<link rel="stylesheet" href="<?= get_theme_dir() ?>css/docproject.css" type="text/css" />
	<link rel="stylesheet" href="<?= get_theme_dir() ?>css/sh/shCore.css" type="text/css" />
	<link rel="stylesheet" href="<?= get_theme_dir() ?>css/sh/shThemeDefault.css" type="text/css" />
</head>
<body>
	<div id="container">
		<div id="top-bar">
			<div class="left">
				<h1 class="left"><a href='<?= get_url('version') ?>'><?= get_app_name(true) ?></a></h1>
				<ul class="left links">
				<?php foreach (get_all_links() as $i => $link) { ?>
					<li><a href="<?= $link->link ?>" title="<?= $link->title ?>" alt="<?= $link->alt ?>"><?= $link->title ?></a> <?php if (($i+1) != count(get_all_links())) { ?>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<?php } ?></li>
				<?php } ?>
				</ul>
			</div>
			<div class="right">
				<?= check_user() ?>
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
			<div id="breadcrumb">
				<a href="#" rel="breadcrumb-slider" title="Index" class="btn-indice"><?= get_text('index') ?></a>
				<?= get_breadcrumb('&raquo;') ?>
			</div>
			<div id="index">
				<?php if (!get_all_topics()) echo '<p>' . get_text('label_no_topic_found') . '</p>'; ?>

				<?php foreach (get_all_topics() as $key => $topic) { ?>
				<dl>
					<dt><a href='<?= $topic->url ?>'><?= $key+1 ?> <?= $topic->title ?></a></dt>
					<?php foreach ($topic->children as $key2 => $subtopic) { ?>
						<dd><a href='<?= $subtopic->url ?>'><?= $key+1 ?>.<?= $key2+1 ?> <?= $subtopic->title ?></a></dd>
					<?php } ?>
				</dl>
				<?php } ?>

				<br clear="all" />
			</div>
		</div>
		<div id="content">
			<?php if (check_user(true) && (get_current_topic() OR new_topic())) { ?>
				<?= get_form('init', 'save_topic') ?>
						<p><label>Language topic:</label> <?= get_current_language('description') . ' (' . get_current_language('code') . ')' ?></p>
						<p><label>Version topic:</label> <?= get_current_version()->description ?></p>
					<?php if (new_topic()) { ?>
						<p>
							<label><?= get_text('label_principal_topic') ?>:</label>
							<input type="radio" name="topic-principal" value="1" />
							Yes
							<input type="radio" name="topic-principal" value="0" checked />
							No
						</p>
						<p>
							<label><?= get_text('label_parent_topic') ?>:</label>
							<select class='medium big' name="topic-parent-id">
								<option value=""><?= get_text('select_parent_topic_default_value') ?></option>
								<?php foreach (get_topic_by('dp_topic_id', 'NULL', null, false) as $topic) { ?>
								<option value="<?= $topic->id ?>"><?= $topic->title ?></option>
								<?php } ?>
							</select>
						</p>
						<?= "<h2><input type='text' name='topic-title' class='big long' value='' /></h2>" ?>
						<div id="main">
							<?= "<textarea name='topic-text' class='long' id='editor'></textarea>" ?>
						</div>
					<?php } else { ?>
						<p>
							<label><?= get_text('label_principal_topic') ?>:</label>
							<input type="radio" name="topic-principal" value="1" <?php if(get_current_topic('principal')) { ?>checked="checked"<?php } ?>/>
							Yes
							<input type="radio" name="topic-principal" value="0" <?php if(!get_current_topic('principal')) { ?>checked="checked"<?php } ?>/>
							No
						</p>
						<p>
							<label><?= get_text('label_parent_topic') ?>:</label>
							<select class="medium big" name="topic-parent-id">
								<option value=""><?= get_text('select_parent_topic_default_value') ?></option>
								<?php foreach (get_topic_by('dp_topic_id', 'NULL', null, false) as $topic) { ?>
								<option value="<?= $topic->id ?>" <?php if (get_topic_by('id', get_current_topic('dp_topic_id'))) { ?>selected<?php } ?>><?= $topic->title ?></option>
								<?php } ?>
							</select>
						</p>
						<?= "<h2><input type='text' name='topic-title' class='big long' value='" . get_current_topic('title') . "' /></h2>" ?>
						<div id="main">
							<?= "<textarea name='topic-text' class='long' id='editor'>" . get_current_topic('text', true) . "</textarea>" ?>
							<?= get_current_topic('text', false, true) ?>
						</div>
					<?php } ?>
				<?= get_form('end', null, array('delete' => array('class' => 'button2'), 'save' => array('class' => 'button2'))) ?>
			<?php } elseif (get_current_topic()) { ?>
				<?= "<h2>" . get_current_topic('title') . "</h2>" ?>
				<div id="main">
					<?= get_current_topic('text') ?>
				</div>
			<?php } else { ?>
				<h2><?= get_text('topic_not_found') ?></h2>
			<?php } ?>
		</div>
		<div id="footer">
			<?= get_footer() ?>
		</div>
	</div>

<script type="text/javascript" language="javascript" src="<?= get_theme_dir() ?>js/sh/shCore.js"></script>
<script type="text/javascript" language="javascript" src="<?= get_theme_dir() ?>js/sh/shBrushAS3.js"></script>
<script type="text/javascript" language="javascript" src="<?= get_theme_dir() ?>js/sh/shBrushCpp.js"></script>
<script type="text/javascript" language="javascript" src="<?= get_theme_dir() ?>js/sh/shBrushCSharp.js"></script>
<script type="text/javascript" language="javascript" src="<?= get_theme_dir() ?>js/sh/shBrushCss.js"></script>
<script type="text/javascript" language="javascript" src="<?= get_theme_dir() ?>js/sh/shBrushDelphi.js"></script>
<script type="text/javascript" language="javascript" src="<?= get_theme_dir() ?>js/sh/shBrushJava.js"></script>
<script type="text/javascript" language="javascript" src="<?= get_theme_dir() ?>js/sh/shBrushJScript.js"></script>
<script type="text/javascript" language="javascript" src="<?= get_theme_dir() ?>js/sh/shBrushPerl.js"></script>
<script type="text/javascript" language="javascript" src="<?= get_theme_dir() ?>js/sh/shBrushPhp.js"></script>
<script type="text/javascript" language="javascript" src="<?= get_theme_dir() ?>js/sh/shBrushPlain.js"></script>
<script type="text/javascript" language="javascript" src="<?= get_theme_dir() ?>js/sh/shBrushPython.js"></script>
<script type="text/javascript" language="javascript" src="<?= get_theme_dir() ?>js/sh/shBrushRuby.js"></script>
<script type="text/javascript" language="javascript" src="<?= get_theme_dir() ?>js/sh/shBrushSql.js"></script>
<script type="text/javascript" language="javascript" src="<?= get_theme_dir() ?>js/sh/shBrushVb.js"></script>
<script type="text/javascript" language="javascript" src="<?= get_theme_dir() ?>js/sh/shBrushXml.js"></script>

<script type="text/javascript" language="javascript" src="<?= get_theme_dir() ?>js/lib/jquery-1.6.2.min.js"></script>
<script type="text/javascript" language="javascript" src="<?= get_theme_dir() ?>js/jquery.textarea.js"></script>
<script type="text/javascript" language="javascript" src="<?= get_theme_dir() ?>js/docproject.js"></script>
<script type="text/javascript" language="javascript" src="<?= get_theme_dir() ?>js/nicEdit.js"></script>
<script type="text/javascript" language="javascript">
SyntaxHighlighter.all();
Docproject.text.confirm_remove_topic = "<?= get_text('confirm_remove_topic') ?>";
Docproject.text.alert_fill_fields = "<?= get_text('alert_fill_fields') ?>";
</script>
</body>
</html>