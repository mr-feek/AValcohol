define([
	'marionette',
	'tpl!templates/user-home-header.html'
], function (
	Mn,
	tpl
) {
	var UserHomeHeaderView = Mn.ItemView.extend({
		template: tpl,
		tagName: 'div',
		className: '',

		events: {},

		ui: {

		},
	});

	return UserHomeHeaderView;
});
