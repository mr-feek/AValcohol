define([
	'marionette',
	'views/CartProductView',
	'App',
	'tpl!templates/cart.html'
], function (
	Mn,
	CartProductView,
	App,
	tpl
) {
	var CartView = Mn.CompositeView.extend({
		template: tpl,
		tagName: 'div',
		className: '',

		childView: CartProductView,

		templateHelpers: function() {
			var view = this;

			return {
				number: App.cart.length
			}
		},

		events: {
			'click @ui.removeFromCart' : 'removeProduct'
		},

		ui: {
			numProducts : '.num-products',
			removeFromCart : '.remove'
		},

		collectionEvents: {
			'update' : 'productsChanged'
		},

		/**
		 * Expects cart to be passed as model for modelEvents purposes
		 * @param options
		 */
		initialize: function (options) {
			this.collection = options.collection;
		},

		productsChanged: function() {
			var number = this.collection.length;
			//this.ui.numProducts.html(number);
			this.render();
		},

		removeProduct: function(e) {
			console.log(e);
			console.log('asdf');
		}
	});

	return CartView;
});
