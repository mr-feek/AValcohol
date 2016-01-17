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

				subtotal: function() {
					// loop through products and multiply price * quantity for combined total
					var total = 0;

					_.each(view.collection.models, function(model) {
						total += model.get('price') * model.get('quantity');
					});

					return Number(total).toFixed(2);
				}
			}
		},

		ui: {
			numProducts : '.num-products',
			checkout : '.checkout',
			continueShopping : '.back-to-shopping'
		},

		events: {
			'click @ui.checkout' : 'showCheckout',
			'click @ui.continueShopping' : 'hideCart'
		},

		collectionEvents: {
			'update' : 'productsChanged',
			'change:quantity' : 'productsChanged' // maybe this could be more efficient
		},

		/**
		 * Expects cart to be passed as model for modelEvents purposes
		 * @param options
		 */
		initialize: function (options) {
			this.collection = options.collection;
		},

		productsChanged: function() {
			this.render();
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
