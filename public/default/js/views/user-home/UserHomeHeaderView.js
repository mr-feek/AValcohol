define([
	'marionette',
	'App',
	'tpl!templates/user-home/user-home-header.html'
], function (
	Mn,
	App,
	tpl
) {
	var UserHomeHeaderView = Mn.ItemView.extend({
		template: tpl,
		tagName: 'div',
		className: '',

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
