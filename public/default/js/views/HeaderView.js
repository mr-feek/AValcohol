define([
	'marionette',
	'App',
	'templates/header.html'
], function (
	Mn,
	App,
	tpl
) {
	var UserHomeHeaderView = Mn.ItemView.extend({
		template: tpl,
		tagName: 'div',
		className: 'top-bar',

		events: {
			'click @ui.logo' : 'goHome'
		},

		ui: {
			'logo' : '.logo'
		},

		goHome: function() {
			App.router.navigate('', {trigger: true});
		}
	});

	return UserHomeHeaderView;
});
