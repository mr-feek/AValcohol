define([
	'marionette',
	'views/cart/CartProductView',
	'App',
	'tpl!templates/cart/cart.html'
], function (
	Mn,
	CartProductView,
	App,
	tpl
) {
	var CartView = Mn.CompositeView.extend({
		template: tpl,
		tagName: 'div',
		className: 'cart-sidebar',
		childView: CartProductView,
		childViewContainer: '.products',

		templateHelpers: function() {
			var view = this;

			return {
				number: App.cart.length,
				subtotal: view.calculateSubtotal
			}
		},

		ui: {
			numProducts : '.num-products',
			checkout : '.checkout',
			continueShopping : '.back-to-shopping',
			subTotal: '.subtotal'
		},

		events: {
			'click @ui.checkout' : 'showCheckout',
			'click @ui.continueShopping' : 'hideCart'
		},

		collectionEvents: {
			'update' : 'updateTotals',
			'change:quantity' : 'updateTotals'
		},

		/**
		 * Expects cart to be passed as model for modelEvents purposes
		 * @param options
		 */
		initialize: function (options) {
			this.collection = options.collection;
			_.bindAll(this, 'calculateSubtotal');
		},

		calculateSubtotal: function() {
			// loop through products and multiply price * quantity for combined total
			var total = 0;

			//debugger;
			_.each(this.collection.models, function(model) {
				total += model.get('pivot').sale_price * model.get('quantity');
			});

			return Number(total).toFixed(2);
		},

		updateTotals: function() {
			var subtotal = this.calculateSubtotal();
			this.ui.subTotal.html('$' + subtotal);
		},

		showCheckout: function(e) {
			e.preventDefault();
			App.rootView.closeOffCanvas(true); // clean up this view
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
