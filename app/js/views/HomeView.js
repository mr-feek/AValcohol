define([
	'marionette',
	'tpl!templates/home.html'
], function(
	Mn,
	tpl
) {
	var HomeView = Mn.ItemView.extend({
		template: tpl,

		events: {
		},

		ui: {
		},

		initialize: function(options) {
		},
	});

	return HomeView;
});
