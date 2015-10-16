define([
	'marionette',
	'tpl!templates/product-categories.html'
], function (Mn,
			 tpl) {
	var ProductCategoriesView = Mn.ItemView.extend({
		template: tpl,

		events: {},

		ui: {},

		initialize: function (options) {
		}
	});

	return ProductCategoriesView;
});
