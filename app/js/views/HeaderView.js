define([
	'marionette',
	'Vent',
	'tpl!templates/header.html'
], function(
	Mn,
	Vent,
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
