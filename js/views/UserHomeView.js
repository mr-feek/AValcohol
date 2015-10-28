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

		onShow: function() {
			App.rootView.getRegion('header').show(new UserHomeHeaderView());
			this.getRegion('sidebar').show(new ProductCategoriesView());
			this.getRegion('products').show(new ProductsView({ endpoint: this.endpoint }));
			App.rootView.getRegion('rightOffCanvas').show(new CartView({ collection : App.cart }));
		},

		toggleCart: function(e) {
			e.preventDefault();
			$('.off-canvas-wrap').foundation('offcanvas', 'show', 'move-left');
		},

		updateNumProducts: function() {
			this.ui.cart.find('i').html(App.cart.length);
		}
	});

	return UserHomeView;
});
