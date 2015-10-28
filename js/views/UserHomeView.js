define([
	'marionette',
	'views/UserHomeHeaderView',
	'views/ProductCategoriesView',
	'views/ProductsView',
	'views/CartView',
	'App',
	'util/Vent',
	'tpl!templates/user-home.html'
], function (
	Mn,
	UserHomeHeaderView,
	ProductCategoriesView,
	ProductsView,
	CartView,
	App,
	Vent,
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
				page: view.endpoint,

				numProducts: function() {
					return App.cart.get('products').length;
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

			Vent.on('home:updateNumProducts', view.updateNumProducts, view);
		},

		onShow: function() {
			App.rootView.getRegion('header').show(new UserHomeHeaderView());
			this.getRegion('sidebar').show(new ProductCategoriesView());
			this.getRegion('products').show(new ProductsView({ endpoint: this.endpoint }));
			App.rootView.getRegion('rightOffCanvas').show(new CartView({ model: App.cart }));
		},

		toggleCart: function() {
			$('.off-canvas-wrap').foundation('offcanvas', 'show', 'move-left');
		},

		updateNumProducts: function() {
			var products = App.cart.get('products').length;
			this.ui.cart.find('i').html(products);
		}
	});

	return UserHomeView;
});
