define([
	'marionette',
	'tpl!templates/user-home/no-products.html'
], function (
	Mn,
	tpl
) {
	var NoProductsView = Mn.ItemView.extend({
		template: tpl,
		className: 'text-center',

		events: {},

		ui: {},

		initialize: function (options) {
		},
	});

	return NoProductsView;
});
