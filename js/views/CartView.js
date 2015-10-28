define([
	'marionette',
	'tpl!templates/cart.html'
], function (
	Mn,
	tpl
) {
	var CartView = Mn.ItemView.extend({
		template: tpl,
		tagName: 'div',
		className: '',

		templateHelpers: function() {
			var view = this;

			return {
				number: view.model.get('products').length
			}
		},

		events: {},

		ui: {
			numProducts : '.num-products'
		},

		modelEvents: {
			'change:products' : 'productsChanged'
		},

		/**
		 * Expects cart to be passed as model for modelEvents purposes
		 * @param options
		 */
		initialize: function (options) {
			this.model = options.model;
		},

		productsChanged: function() {
			var number = this.model.get('products').length;
			//this.ui.numProducts.html(number);
			this.render();

			console.log(this.model.get('products'));
		}
	});

	return CartView;
});
