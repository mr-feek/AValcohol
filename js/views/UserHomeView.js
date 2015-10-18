define([
	'marionette',
	'views/ProductCategoriesView',
	'views/ProductsView',
	'tpl!templates/user-home.html'
], function (
	Mn,
	ProductCategoriesView,
	ProductsView,
	tpl
) {
	var UserHomeView = Mn.LayoutView.extend({
		template: tpl,

		events: {},

		ui: {},

		regions: {
			sidebar : '#sidebar',
			products : '#products'
		},

		/**
		 *
		 * @param options
		 * 	- endpoint (optional)
		 */
		initialize: function (options) {
			if (options.endpoint) {
				this.endpoint = options.endpoint;
			} else {
				this.endpoint = 'featured';
			}
		},

		onShow: function() {
			this.getRegion('sidebar').show(new ProductCategoriesView());
			this.getRegion('products').show(new ProductsView({ endpoint: this.endpoint }));
		}
	});

	return UserHomeView;
});
