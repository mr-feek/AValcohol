define([
	'marionette',
	'tpl!templates/customer-info-collection.html'
], function(
	Mn,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,

		events: {
		},

		ui: {
		},

		initialize: function(options) {
		}
	});

	return view;
});
