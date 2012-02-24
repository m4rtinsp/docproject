$(function(){
	Docproject._init();
});

Docproject = {

	text: {
		confirm_remove_topic: '',
		alert_fill_fields: ''
	},

	_init: function() {
		this.login();
		this.language();
		this.breadcrumb_slider();
		this.versions_slider();
		this._clear_field();
		this._delete();
		this._external();
		this._tab();
		this.validate_form_topic();
		this.editor();
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

	_delete: function() {
		$('.dp-delete-item').click(function(){
			if (!confirm(Docproject.text.confirm_remove_topic)) {
				return false;
			}
			else {
				var id = $(this).attr('id').split('item-')[1];
				var lang = Docproject._get('lang');
					lang = lang ? '&lang=' + lang : '';
				var version = Docproject._get('version');
					version = version ? '&version=' + version : '';

				location.href = 'docproject/dp-topic.php?action=delete&post=' + id + lang + version;
			}
		});
	},

	_external: function() {
		$('[rel=external]').click(function(e){
			e.preventDefault();
			window.open($(this).attr('href'))
		});
	},

	_tab: function() {
		$('textarea[name=topic-text]').tabby();
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
			var pos = '';
			
			if ($('div#modal-login').hasClass('on'))
				$('div#modal-login').fadeOut('fast').removeClass('on');

			if ($('div#modal-language').hasClass('on'))
				$('div#modal-language').fadeOut('fast').removeClass('on');
			else
				$('div#modal-language').fadeIn('fast').addClass('on');
		});
	},

	breadcrumb_slider: function() {
		$('a[rel=breadcrumb-slider]').click(function(e){
			e.preventDefault();

			$('div#index').slideToggle();
		});
	},

	versions_slider: function() {
		$('a[rel=versions-slider]').click(function(e){
			e.preventDefault();

			$('ul#list-versions').slideToggle();
		});
	},

	validate_form_topic: function() {
		$('form[name=formTopic]').submit(function(){
			if (!$(this).find('input[name=topic-title]').val()) {
				alert(Docproject.text.alert_fill_fields);
				return false;
			}
		});
	},

	editor: function() {
		bkLib.onDomLoaded(function() {
			new nicEditor({iconsPath : 'themes/default/img/nicEditorIcons.gif', buttonList : ['bold','italic','underline','left','center','right','html']}).panelInstance('editor');
		});
	}

}