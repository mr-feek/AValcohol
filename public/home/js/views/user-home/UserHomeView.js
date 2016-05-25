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
			return {
				numProducts: function() {
					return App.cart.length;
				},

				blastMessage: App.config.get('blastMessage')
			}
		},

		/**
		 *
		 * @param options
		 */
		initialize: function (options) {
			App.cart.on('update', this.updateNumProducts, this);
		},

		onBeforeShow: function() {
			App.rootView.getRegion('header').show(new UserHomeHeaderView());
			this.getRegion('products').show(new ProductsView());
			App.rootView.getRegion('rightOffCanvas').show(new CartView({ collection : App.cart }));
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
