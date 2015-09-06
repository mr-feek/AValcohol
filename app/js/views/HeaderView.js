define([
	'marionette',
	'tpl!templates/header.html'
], function(
	Mn,
	tpl
) {
	var HeaderView = Mn.ItemView.extend({
		template: tpl,

		events: {
		},

		ui: {
		},

		initialize: function(options) {
		},
	});

	return HeaderView;
});
