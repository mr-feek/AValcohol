define([
	'marionette',
	'views/HeaderView',
	'views/user-home/ProductCategoriesView',
	'views/user-home/ProductsView',
	'views/cart/CartView',
	'views/user-home/StoreClosedView',
	'App',
	'tpl!templates/user-home/user-home.html'
], function (
	Mn,
	HeaderView,
	ProductCategoriesView,
	ProductsView,
	CartView,
	StoreClosedView,
	app,
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
			return {
				numProducts: function() {
					return app.cart.length;
				},

				blastMessage: app.config.get('blastMessage')
			}
		},

		/**
		 *
		 * @param options
		 */
		initialize: function (options) {
			app.cart.on('update', this.updateNumProducts, this);
		},

		onBeforeShow: function() {
			app.rootView.getRegion('header').show(new HeaderView());
			this.getRegion('products').show(new ProductsView());

			if (app.config.get('isClosed')) {
				app.rootView.getRegion('modalRegion').show(new StoreClosedView());
			}
		},

		onRender: function() {
			if (app.config.get('isClosed') === true) {
				this.ui.cart.hide();
			}
		},

		openCart: function(e) {
			if (e) {
				e.preventDefault();
			}

			if (! app.rootView.getRegion('rightOffCanvas').hasView()) {
				app.rootView.getRegion('rightOffCanvas').show(new CartView({ collection : app.cart }));
			}
			
			app.rootView.openOffCanvas();
		},

		updateNumProducts: function() {
			// for some reason need to rewrap the cart in jquery selector, otherwise issues when route changes in user home
			$(this.ui.cart).find('i').html(app.cart.length);
		}
	});

	return UserHomeView;
});
