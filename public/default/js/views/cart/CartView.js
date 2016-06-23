define([
	'marionette',
	'views/cart/CartProductView',
	'views/cart/EmptyCartView',
	'App',
	'tpl!templates/cart/cart.html'
], function (
	Mn,
	CartProductView,
	EmptyCartView,
	App,
	tpl
) {
	var CartView = Mn.CompositeView.extend({
		template: tpl,
		tagName: 'div',
		className: 'cart-sidebar',
		childView: CartProductView,
		childViewContainer: '.products',
		emptyView: EmptyCartView,

		templateHelpers: function() {
			return {
				number: App.cart.models.length,
				subtotal: App.cart.calculateSubtotal,
				tax: App.cart.calculateTax,
				deliveryFee: App.cart.calculateDeliveryFee,
				total: App.cart.calculateTotal
			}
		},

		ui: {
			numProducts : '.num-products',
			checkout : '.go-to-checkout',
			continueShopping : '.back-to-shopping',
			subTotal: '.subtotal'
		},

		events: {
			'click @ui.checkout' : 'showCheckout',
			'click @ui.continueShopping' : 'hideCart'
		},

		collectionEvents: {
			'update' : 'collectionChanged',
			'change:quantity' : 'collectionChanged'
		},

		/**
		 * Expects cart to be passed as model for modelEvents purposes
		 * @param options
		 */
		initialize: function (options) {
			this.collection = options.collection;
		},

		onRender: function() {
			this.checkIfCartShouldBeDisabled();
		},

		checkIfCartShouldBeDisabled: function() {
			if (this.collection.length == 0) {
				this.ui.checkout.addClass('disabled');
			} else {
				this.ui.checkout.removeClass('disabled');
			}
		},

		collectionChanged: function() {
			this.checkIfCartShouldBeDisabled();
			this.updateTotals();
		},

		updateTotals: function() {
			this.render();
		},

		showCheckout: function(e) {
			e.preventDefault();
			if (this.ui.checkout.hasClass('disabled')) {
				return;
			}
			App.rootView.trigger('closeOffCanvas', true); // clean up this view
			App.router.navigate('checkout', {trigger: true});
		},

		/**
		 * close the cart view
		 * @param e
		 */
		hideCart: function(e) {
			App.rootView.closeOffCanvas(false); // don't clean up this view, just hide it
		}
	});

	return CartView;
});
