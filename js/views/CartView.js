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

		ui: {
			numProducts : '.num-products',
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
			this.render();
		}
	});

	return CartView;
});
