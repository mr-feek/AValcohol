define([
	'marionette',
	'views/user-home/UserHomeHeaderView',
	'views/user-home/ProductCategoriesView',
	'views/user-home/ProductsView',
	'views/cart/CartView',
	'App',
	'tpl!templates/user-home/user-home.html'
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
		className: '',

		events: {
			'click @ui.cart' : 'openCart'
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
				page: view.endpoint,

				numProducts: function() {
					return App.cart.length;
				}
			}
		},

		/**
		 *
		 * @param options
		 * 	- endpoint (optional)
		 */
		initialize: function (options) {
			var view = this;

			if (options.endpoint) {
				this.endpoint = options.endpoint;
			} else {
				this.endpoint = 'featured';
			}

			App.cart.on('update', view.updateNumProducts, view);
		},

		onBeforeShow: function() {
			App.rootView.getRegion('header').show(new UserHomeHeaderView());
			this.getRegion('sidebar').show(new ProductCategoriesView({ endpoint: this.endpoint }));
			this.getRegion('products').show(new ProductsView({ endpoint: this.endpoint }));
			App.rootView.getRegion('rightOffCanvas').show(new CartView({ collection : App.cart }));
		},

		/**
		 * called from controller as a fast way to swap product views
		 * @param endpoint
		 */
		showDifferentProductView: function(endpoint) {
			this.endpoint = endpoint;
			this.getRegion('sidebar').currentView.endpoint = endpoint;
			this.getRegion('products').show(new ProductsView({ endpoint: endpoint }));
			this.getRegion('sidebar').currentView.updateActiveLink();
		},

		openCart: function(e) {
			e.preventDefault();
			App.rootView.openOffCanvas();
		},

		updateNumProducts: function() {
			// for some reason need to rewrap the cart in jquery selector, otherwise issues when route changes in user home
			$(this.ui.cart).find('i').html(App.cart.length);
		}
	});

	return UserHomeView;
});
