define([
	'marionette',
	'views/HeaderView',
	'views/user-home/ProductCategoriesView',
	'views/user-home/ProductsView',
	'views/cart/CartView',
	'views/user-home/StoreClosedView',
	'views/user-home/SidebarView',
	'foundationEqualizer',
	'App',
	'tpl!templates/user-home/user-home.html'
], function (
	Mn,
	HeaderView,
	ProductCategoriesView,
	ProductsView,
	CartView,
	StoreClosedView,
	SidebarView,
	FoundationEqualizer,
	app,
	tpl
) {
	var UserHomeView = Mn.LayoutView.extend({
		template: tpl,
		className: '',
		cartHasAlreadyOpened: false, // flag for knowing if we have already automatically opened the cart before. we only want to do this once.

		events: {
			'click @ui.cart' 			: 'openCart',
			'click @ui.changeAddress' 	: 'changeAddress'
		},

		ui: {
			'equalizerWrapper' 	: '#equalizer-wrapper',
			'cart' 				: '#cart',
			'changeAddress'		: '.change-address'
		},

		regions: {
			sidebar 		: '#sidebar',
			products 		: '#products'
		},

		templateHelpers: function() {
			return {
				address: function() {
					return app.user.get('address').getDisplayableAddress();
				},
				numProducts: function() {
					return app.cart.getNumberOfItemsInCart();
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
			app.cart.on('change:quantity', this.updateNumProducts, this);
		},

		onBeforeShow: function() {
			app.rootView.getRegion('header').show(new HeaderView());
			this.getRegion('products').show(new ProductsView());
			this.getRegion('sidebar').show(new SidebarView());

			if (app.config.get('isClosed')) {
				app.rootView.getRegion('modalRegion').show(new StoreClosedView());
			}
		},

		onShow: function() {
			this.reflowEqualizer();
		},

		reflowEqualizer: function() {
			//Foundation.reInit('equalizer');
			Foundation.reInit(this.ui.equalizerWrapper);
		},

		onRender: function() {
			if (app.config.get('isClosed') === true) {
				this.ui.cart.hide();
			}

			if (this.equalizerInitialized) {
				return;
			}

			var elem = new Foundation.Equalizer(this.ui.equalizerWrapper);
			this.equalizerInitialized = true;
		},

		openCart: function(e) {
			if (e) {
				e.preventDefault();
			}

			if (! app.rootView.getRegion('offCanvas').hasView()) {
				app.rootView.getRegion('offCanvas').show(new CartView({ collection : app.cart }));
			}
			
			app.rootView.trigger('openOffCanvas', e);
		},

		checkIfShouldOpenCart: function(evt) {
			if (this.cartHasAlreadyOpened) {
				return;
			}

			this.openCart(evt);
			this.cartHasAlreadyOpened = true;
		},

		updateNumProducts: function() {
			// for some reason need to rewrap the cart in jquery selector, otherwise issues when route changes in user home
			var $cart = $(this.ui.cart).find('i');

			$cart.animateCss('bounceInDown', {removeAnimateClass: true});
			$cart.html(app.cart.getNumberOfItemsInCart());
		},

		/**
		 * todo: reset user address to a fresh user address
		 *
		 * for now just redirects back to home so they can change it there
		 */
		changeAddress: function() {
			app.router.navigate('/', {trigger: true});
		}
	});

	return UserHomeView;
});
