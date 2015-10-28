define([
	'marionette',
	'views/UserHomeHeaderView',
	'views/ProductCategoriesView',
	'views/ProductsView',
	'views/CartView',
	'App',
	'tpl!templates/user-home.html'
], function (
	Mn,
	UserHomeHeaderView,
	ProductCategoriesView,
	ProductsView,
	CartView,
	App,
	tpl
) {
	var UserHomeView = Mn.LayoutView.extend({
		template: tpl,

		events: {
			'click @ui.cart' : 'toggleCart'
		},

		ui: {
			'cart' : '#cart'
		},

		regions: {
			sidebar : '#sidebar',
			products : '#products'
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
			App.rootView.getRegion('header').show(new UserHomeHeaderView());
			this.getRegion('sidebar').show(new ProductCategoriesView());
			this.getRegion('products').show(new ProductsView({ endpoint: this.endpoint }));
			App.rootView.getRegion('rightOffCanvas').show(new CartView({ model: App.cart }));
		},

		toggleCart: function() {
			$('.off-canvas-wrap').foundation('offcanvas', 'show', 'move-left');
		}
	});

	return UserHomeView;
});
