define([
	'marionette',
	'views/ProductCategoriesView',
	'views/FeaturedProductsView',
	'views/AllProductsView',
	'tpl!templates/user-home.html'
], function (
	Mn,
	ProductCategoriesView,
	FeaturedProductsView,
	AllProductsView,
	tpl
) {
	var UserHomeView = Mn.LayoutView.extend({
		template: tpl,

		events: {},

		ui: {},

		regions: {
			sidebar : '#sidebar',
			featured : '#featured',
			products : '#products'
		},

		initialize: function (options) {
		},

		onShow: function() {
			this.getRegion('sidebar').show(new ProductCategoriesView());
			this.getRegion('featured').show(new FeaturedProductsView());
			this.getRegion('products').show(new AllProductsView());
		}
	});

	return UserHomeView;
});
