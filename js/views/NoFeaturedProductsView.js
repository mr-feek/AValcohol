define([
	'marionette',
	'tpl!templates/no-featured-products.html'
], function (Mn,
			 tpl) {
	var NoFeaturedProductsView = Mn.ItemView.extend({
		template: tpl,

		events: {},

		ui: {},

		initialize: function (options) {
		},
	});

	return NoFeaturedProductsView;
});
