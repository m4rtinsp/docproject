$(function(){
	Docproject._init();
});

Docproject = {

	text: {
		confirm_remove_item: '',
		confirm_remove_item_version: '',
		confirm_remove_item_language: '',
		alert_updated_positions: '',
		alert_updated_positions_false: '',
		alert_fill_fields: '',
		admin_button_set_as_default: ''
	},

	_init: function() {
		this.login();
		this.language();
		this._clear_field();
		this._external();
		
		this.save_positions();

		this.validate_form_user();
		this.delete_user();
		this.edit_user();
		
		this.list_on();
		this.set_theme();
		
		this.validate_form_language();
		this.delete_language();
		this.edit_language();
		this.set_language();
		
		this.validate_form_config();
		
		this.edit_link();
		this.delete_link();
		this.validate_form_link();

		this.delete_version();
		this.edit_version();
		this.validate_form_version();
		this.versions_slider();
		this.set_version();
	},

	_get: function(param) {
		var url = location.search;
			url = url.split(param+'=');
			url = url[1] ? url[1].split('&')[0] : null;

		return url;
	},

	_clear_field: function() {
		$('input[name=username]').click(function(){ $(this).val(''); })
		$('input[name=password]').click(function(){ $(this).val(''); })
	},

	_external: function() {
		$('[rel=external]').click(function(e){
			e.preventDefault();
			window.open($(this).attr('href'))
		});
	},

	_lang: function() {
		var lang = Docproject._get('lang');
		return lang ? '&lang=' + lang : '';
	},

	login: function() {
		$('a[rel=login]').click(function(e){
			e.preventDefault();

			$('#formLogin').attr('action', $('#formLogin').attr('action') + location.search)

			if ($('div#modal-language').hasClass('on'))
				$('div#modal-language').fadeOut('fast').removeClass('on');

			if ($('div#modal-login').hasClass('on'))
				$('div#modal-login').fadeOut('fast').removeClass('on');
			else
				$('div#modal-login').fadeIn('fast').addClass('on');
		});
	},

	language: function() {
		$('a[rel=language]').click(function(e){
			e.preventDefault();

			if ($('div#modal-login').hasClass('on'))
				$('div#modal-login').fadeOut('fast').removeClass('on');

			if ($('div#modal-language').hasClass('on'))
				$('div#modal-language').fadeOut('fast').removeClass('on');
			else
				$('div#modal-language').fadeIn('fast').addClass('on');
		});
	},

	save_positions: function() {
		$("a[rel='save-positions']").click(function(e){
			e.preventDefault();
			var ids = [];
			var pos = [];

			$('div#tab-topics input[type=text]').each(function(){
				ids.push($(this).attr('id'));
				pos.push($(this).val());
			});
			
			$.ajax({
				type: 'post',
				url: 'docproject/dp-topic.php/?action=positions',
				dataType: 'json',
				data: {'ids[]': ids, 'pos': pos},
				success: function(response) {
					if (response)
						alert(Docproject.text.alert_updated_positions);
					else
						alert(Docproject.text.alert_updated_positions_false);
				}
			})
		});
	},

	validate_form_user: function() {
		$('#userForm').submit(function(){
			var $this = $(this);
			var user = $(this).find('input[name=user]').val();
			var pass = $(this).find('input[name=password]').val();
			var mail = $(this).find('input[name=email]').val();
			var id = $(this).find('input[name=id]').val();

			if (!user || !pass) {
				alert(Docproject.text.alert_fill_fields);
				return false;
			}

			$.post
			(
				'docproject/dp-user.php?action=save' + Docproject._lang(),
				'username=' + user + '&password=' + pass + '&email=' + mail + '&id=' + id,
				function(response) {
					if (response.status) {
						if (!id) {
							var li = "<li id='item" + response.user.id + "'>";
								li += "<label><strong>" + response.user.username + "</strong> - " + response.user.email + "</label>";
								li += "<a href='#' id='id" + response.user.id + "' rel='edit-user'>[ " + response.text.edit + " ]</a> ";
								li += "<a href='#' id='id" + response.user.id + "' rel='delete-user'>[ " + response.text.delete + " ]</a>";
								li += "</li>";

							$('ul#list-users').append(li);
						}
						else {
							var li = "<label><strong>" + response.user.username + "</strong> - " + response.user.email + "</label>";
								li += "<a href='#' id='id" + response.user.id + "' rel='edit-user'>[ " + response.text.edit + " ]</a> ";
								li += "<a href='#' id='id" + response.user.id + "' rel='delete-user'>[ " + response.text.delete + " ]</a>";
								
							$('ul#list-users li#item' + id).html(li);
						}

						$this.find('input[name=user]').val('');
						$this.find('input[name=password]').val('');
						$this.find('input[name=email]').val('');
						$this.find('input[name=id]').val('');

						Docproject.list_on();
						Docproject.delete_user();
						Docproject.edit_user();
					}
				}, 'json'
			)

			return false;
		});
	},

	validate_form_language: function() {
		$('#userLanguage').submit(function(){
			var $this = $(this);
			var description = $(this).find('input[name=description]').val();
			var code = $(this).find('input[name=code]').val();
			var id = $(this).find('input[name=id]').val();

			if (!description || !code) {
				alert(Docproject.text.alert_fill_fields);
				return false;
			}

			$.post
			(
				'docproject/dp-language.php?action=save',
				'description=' + description + '&code=' + code + '&id=' + id + '&lang=' + Docproject._lang(),
				function(response) {
					if (response.status) {
						if (!id) {
							var li = "<li id='item" + response.language.id + "'>";
								li += "<label><strong>" + response.language.description + "</strong> - " + response.language.code + "</label>";
								li += "<a href='' id='id" + response.language.id + "' rel='edit-language'>[ " + response.text.edit + " ]</a> ";
								li += "<a href='#' id='id" + response.language.id + "' rel='delete-language'>[ " + response.text.delete + " ]</a> ";
								li += "<a href='#' id='id" + response.language.id + "' rel='set-language'>[ " + Docproject.text.admin_button_set_as_default + " ]</a>";
								li += "</li>";

							$('ul#list-languages').append(li);
						}
						else {
							var li = "<label><strong>" + response.language.description + "</strong> - " + response.language.code + "</label>";
								li += "<a href='' id='id" + response.language.id + "' rel='edit-language'>[ " + response.text.edit + " ]</a>";
								li += "<a href='#' id='id" + response.language.id + "' rel='delete-language'>[ " + response.text.delete + " ]</a>";
								li += "<a href='#' id='id" + response.language.id + "' rel='set-language'>[ " + Docproject.text.admin_button_set_as_default + " ]</a>";
							
							$('ul#list-languages li#item' + id).html(li);
						}

						$this.find('input[name=description]').val('');
						$this.find('input[name=code]').val('');
						$this.find('input[name=id]').val('');

						Docproject.list_on();
						Docproject.delete_language();
						Docproject.edit_language();
						Docproject.set_language();
					}
				}, 'json'
			)

			return false;
		});
	},

	validate_form_link: function() {
		$('#userLinks').submit(function(){
			var $this = $(this);
			var title = $(this).find('input[name=title]').val();
			var alt = $(this).find('input[name=alt]').val();
			var url = $(this).find('input[name=url]').val();
			var id = $(this).find('input[name=id]').val();

			if (!title || !url) {
				alert(Docproject.text.alert_fill_fields);
				return false;
			}

			$.post
			(
				'docproject/dp-link.php?action=save',
				'title=' + title + '&alt=' + alt + '&url=' + url + '&id=' + id,
				function(response) {
					if (response.status) {
						if (!id) {
							var li = "<li id='item" + response.link.id + "'>";
								li += "<label><strong>" + response.link.title + "</strong> - " + response.link.alt + " - " + response.link.link + "</label>";
								li += "<a href='' id='id" + response.link.id + "' rel='edit-link'>[ " + response.text.edit + " ]</a> ";
								li += "<a href='#' id='id" + response.link.id + "' rel='delete-link'>[ " + response.text.delete + " ]</a> ";
								li += "</li>";

							$('ul#list-links').append(li);
						}
						else {
							var li = "<label><strong>" + response.link.title + "</strong> - " + response.link.alt + " - " + response.link.link + "</label>";
								li += "<a href='' id='id" + response.link.id + "' rel='edit-link'>[ " + response.text.edit + " ]</a><a href='#' id='id" + response.link.id + "' rel='delete-link'>[ " + response.text.delete + " ]</a>";
							
							$('ul#list-links li#item' + id).html(li);
						}

						$this.find('input[name=title]').val('');
						$this.find('input[name=alt]').val('');
						$this.find('input[name=url]').val('');
						$this.find('input[name=id]').val('');

						Docproject.list_on();
						Docproject.delete_link();
						Docproject.edit_link();
					}
				}, 'json'
			)

			return false;
		});
	},

	validate_form_version: function() {
		$('#userVersions').submit(function(){
			var $this = $(this);
			var description = $(this).find("input[name='name-version']").val();
			var id = $(this).find('input[name=id]').val();

			if (!description) {
				alert(Docproject.text.alert_fill_fields);
				return false;
			}

			$.post
			(
				'docproject/dp-version.php?action=save',
				'description=' + description + '&id=' + id,
				function(response) {
					if (response.status) {
						if (!id) {
							var li = "<li id='item" + response.version.id + "'>";
								li += "<label>" + response.version.description + "</label>";
								li += "<a href='' id='id" + response.version.id + "' rel='edit-link'>[ " + response.text.edit + " ]</a> ";
								li += "<a href='#' id='id" + response.version.id + "' rel='delete-link'>[ " + response.text.delete + " ]</a> ";
								li += "<a href='#' id='id" + response.version.id + "' rel='set-version'>[ " + Docproject.text.admin_button_set_as_default + " ]</a>";
								li += "</li>";

							$('ul#list-versions-site').append(li);
						}
						else {
							var li = "<label>" + response.version.description + "</label>";
								li += "<a href='' id='id" + response.version.id + "' rel='edit-link'>[ " + response.text.edit + " ]</a> ";
								li += "<a href='#' id='id" + response.version.id + "' rel='delete-link'>[ " + response.text.delete + " ]</a>";
								li += "<a href='#' id='id" + response.version.id + "' rel='set-version'>[ " + Docproject.text.admin_button_set_as_default + " ]</a>";
							
							$('ul#list-versions-site li#item' + id).html(li);
						}

						$this.find("input[name='name-version']").val('');
						$this.find('input[name=id]').val('');

						Docproject.list_on();
						Docproject.delete_version();
						Docproject.edit_version();
						Docproject.set_version();
					}
				}, 'json'
			)

			return false;
		});
	},

	edit_user: function() {
		$('a[rel=edit-user]').unbind('click');
		$('a[rel=edit-user]').bind('click', function(e){
			e.preventDefault();

			var $this = $(this);
			var id = $(this).attr('id').split('id')[1];

			$.get
			(
				'docproject/dp-user.php',
				'action=get&id=' + id,
				function(response) {
					if (response.status) {
						$('#userForm').find('input[name=user]').val(response.user.username);
						$('#userForm').find('input[name=email]').val(response.user.email);
						$('#userForm').find('input[name=id]').val(response.user.id);
					}
				}
			), 'json'
		});
	},

	edit_link: function() {
		$('a[rel=edit-link]').unbind('click');
		$('a[rel=edit-link]').bind('click', function(e){
			e.preventDefault();

			var $this = $(this);
			var id = $(this).attr('id').split('id')[1];

			$.get
			(
				'docproject/dp-link.php',
				'action=get&id=' + id,
				function(response) {
					if (response.status) {
						$('#userLinks').find('input[name=title]').val(response.link.title);
						$('#userLinks').find('input[name=alt]').val(response.link.alt);
						$('#userLinks').find('input[name=url]').val(response.link.url);
						$('#userLinks').find('input[name=id]').val(response.link.id);
					}
				}
			), 'json'
		});
	},

	edit_language: function() {
		$('a[rel=edit-language]').unbind('click');
		$('a[rel=edit-language]').bind('click', function(e){
			e.preventDefault();

			var $this = $(this);
			var id = $(this).attr('id').split('id')[1];

			$.get
			(
				'docproject/dp-language.php',
				'action=get&id=' + id,
				function(response) {
					if (response.status) {
						$('#userLanguage').find('input[name=description]').val(response.language.description);
						$('#userLanguage').find('input[name=code]').val(response.language.code);
						$('#userLanguage').find('input[name=id]').val(response.language.id);
					}
				}
			), 'json'
		});
	},

	edit_version: function() {
		$('a[rel=edit-version]').unbind('click');
		$('a[rel=edit-version]').bind('click', function(e){
			e.preventDefault();

			var $this = $(this);
			var id = $(this).attr('id').split('id')[1];

			$.get
			(
				'docproject/dp-version.php',
				'action=get&id=' + id,
				function(response) {
					if (response.status) {
						$('#userVersions').find("input[name='name-version']").val(response.version.description);
						$('#userVersions').find("input[name='id']").val(response.version.id);
					}
				}
			), 'json'
		});
	},

	delete_link: function() {
		$('a[rel=delete-link]').unbind('click');
		$('a[rel=delete-link]').bind('click', function(e){
			e.preventDefault();

			var $this = $(this);
			var id = $(this).attr('id').split('id')[1];

			if (!confirm(Docproject.text.confirm_remove_item))
				return false;
			
			$.get
			(
				'docproject/dp-link.php',
				'action=delete&id=' + id,
				function(response) {
					if (response.status)
						$this.parent().fadeOut('fast', function() { $(this).remove() });
				}
			), 'json'
		});
	},

	delete_language: function() {
		$('a[rel=delete-language]').unbind('click');
		$('a[rel=delete-language]').bind('click', function(e){
			e.preventDefault();

			var $this = $(this);
			var id = $(this).attr('id').split('id')[1];

			if (!confirm(Docproject.text.confirm_remove_item_language))
				return false;
			
			$.get
			(
				'docproject/dp-language.php',
				'action=delete&id=' + id,
				function(response) {
					if (response.status)
						$this.parent().fadeOut('fast', function() { $(this).remove() });
					else
						alert(response.message);
				}
			), 'json'
		});
	},

	delete_user: function() {
		$('a[rel=delete-user]').unbind('click');
		$('a[rel=delete-user]').bind('click', function(e){
			e.preventDefault();

			var $this = $(this);
			var id = $(this).attr('id').split('id')[1];

			if (!confirm(Docproject.text.confirm_remove_item))
				return false;
			
			$.get
			(
				'docproject/dp-user.php',
				'action=delete&id=' + id,
				function(response) {
					if (response.status)
						$this.parent().fadeOut('fast', function() { $(this).remove() });
				}
			), 'json'
		});
	},

	delete_version: function() {
		$('a[rel=delete-version]').unbind('click');
		$('a[rel=delete-version]').bind('click', function(e){
			e.preventDefault();

			var $this = $(this);
			var id = $(this).attr('id').split('id')[1];

			if (!confirm(Docproject.text.confirm_remove_item_version))
				return false;
			
			$.get
			(
				'docproject/dp-version.php',
				'action=delete&id=' + id,
				function(response) {
					if (response.status)
						$this.parent().fadeOut('fast', function() { $(this).remove() });
					else
						alert(response.message);
				}
			), 'json'
		});
	},

	list_on: function() {
		$('#content ul#list-users li, #content ul#list-languages li, #content ul#list-versions-site li, #content ul#list-links li').hover(function(){
			$(this).find('a').show();
		}, function(){
			$(this).find('a').hide();
		});
	},

	set_theme: function() {
		$('input.set-theme').click(function(){
			var code = $(this).val();

			$.get
			(
				'docproject/dp-theme.php',
				'action=save&code=' + code + Docproject._lang(),
				function(response) {
					if (response.status)
						alert(response.text.theme_set_success.replace('%code%', code));
					else
						alert(response.text.theme_set_false.replace('%code%', code));
				}
			), 'json'
		});
	},

	set_language: function() {
		$('a[rel=set-language]').unbind('click');
		$('a[rel=set-language]').bind('click', function(e){
			e.preventDefault();

			var $this = $(this);
			var id = $(this).attr('id').split('id')[1];

			$.get
			(
				'docproject/dp-language.php',
				'action=set_default&id=' + id + Docproject._lang(),
				function(response) {
					if (response.status) {						
						$('a[rel=set-language]').text('[ ' + response.text.admin_button_set_as_default + ' ]');	
						$this.text('- ' + response.text.admin_button_is_default);
					}
				}, 'json'
			);
		});
	},

	set_version: function() {
		$('a[rel=set-version]').unbind('click');
		$('a[rel=set-version]').bind('click', function(e){
			e.preventDefault();

			var $this = $(this);
			var id = $(this).attr('id').split('id')[1];

			$.get
			(
				'docproject/dp-version.php',
				'action=set_default&id=' + id + Docproject._lang(),
				function(response) {
					if (response.status) {						
						$('a[rel=set-version]').text('[ ' + response.text.admin_button_set_as_default + ' ]');	
						$this.text('- ' + response.text.admin_button_is_default);
					}
				}, 'json'
			);
		});
	},

	validate_form_config: function() {
		$('#formConfig').submit(function(){
			if (!$(this).find('input[name=appname]').val()) {
				alert(Docproject.text.alert_fill_fields);
				return false;
			}
		});
	},

	versions_slider: function() {
		$('a[rel=versions-slider]').click(function(e){
			e.preventDefault();

			$('ul#list-versions').slideToggle();
		});
	}

}