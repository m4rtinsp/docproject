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

	<title>Doc Project</title>
	<link rel="stylesheet" href="install/css/reset.css" type="text/css" />
	<link rel="stylesheet" href="install/css/docproject.css" type="text/css" />
</head>
<body>
	<div id="container">
		<h1>DOC PROJECT</h1>
		<h2>INSTALAÇÃO</h2>

		<ul>
			<?php if (dbcheck()) { ?>
				<li><span class="sucess"></span> Configurações do Banco de dados</li>
			<?php } else { ?>
				<li><span class="error"></span> Configurações do Banco de dados (configure os dados de acesso em: config.php)</li>
			<?php } ?>

			<?php if (dbcreate()) { ?>
				<li><span class="sucess"></span> Banco de dados criado</li>
			<?php } else { ?>
				<li><span class="error"></span> Banco de dados não criado</li>
			<?php } ?>

			<?php if (dbtables()) { ?>
				<li><span class="sucess"></span> Tabelas criadas</li>
			<?php } else { ?>
				<li><span class="error"></span> Tabelas não criadas</li>
			<?php } ?>

			<?php if (is_writable('locale/blank.txt')) { ?>
			<li><span class="sucess"></span> Permissão de leitura/escrita na pasta locale</li>
			<?php } else { ?>
			<li><span class="error"></span> Permissão de leitura/escrita na pasta locale</li>
			<?php } ?>

			<?php if (is_writable('public/blank.txt')) { ?>
			<li><span class="sucess"></span> Permissão de leitura/escrita na pasta public</li>
			<?php } else { ?>
			<li><span class="error"></span> Permissão de leitura/escrita na pasta public</li>
			<?php } ?>
		</ul>

		<?php if (dbtables()) : ?>
		<p>Pronto! A configuração foi concluída. Remova ou renomeie a pasta INSTALL e atualize seu browser.</p>
		<?php endif; ?>
	</div>
</body>
</html>