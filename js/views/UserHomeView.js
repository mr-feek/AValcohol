define([
	'marionette',
	'views/ProductCategoriesView',
	'views/FeaturedProductsView',
	'tpl!templates/user-home.html'
], function (Mn,
			 ProductCategoriesView,
			 FeaturedProductsView,
			 tpl) {
	var UserHomeView = Mn.LayoutView.extend({
		template: tpl,

		events: {},

		ui: {},

		regions: {
			sidebar : '#sidebar',
			featured : '#featured'
		},

		initialize: function (options) {
		},

		onShow: function() {
			this.getRegion('sidebar').show(new ProductCategoriesView());
			this.getRegion('featured').show(new FeaturedProductsView());
		}
	});

	return UserHomeView;
});
