define([
	'marionette',
	'views/UserHomeHeaderView',
	'views/ProductCategoriesView',
	'views/ProductsView',
	'App',
	'tpl!templates/user-home.html'
], function (
	Mn,
	UserHomeHeaderView,
	ProductCategoriesView,
	ProductsView,
	App,
	tpl
) {
	var UserHomeView = Mn.LayoutView.extend({
		template: tpl,

		events: {},

		ui: {},

		regions: {
			sidebar : '#sidebar',
			products : '#products',
			cart : '#cart'
		},

		templateHelpers: function() {
			var view = this;

			return {
				page: view.endpoint
			}
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
			App.rootView.getRegion('header').show(new UserHomeHeaderView({ model: App.cart}));
			this.getRegion('sidebar').show(new ProductCategoriesView());
			this.getRegion('products').show(new ProductsView({ endpoint: this.endpoint }));
		}
	});

	return UserHomeView;
});
