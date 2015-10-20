define([
	'marionette',
	'models/Cart',
	'App',
	'tpl!templates/product.html'
], function (
	Mn,
	Cart,
	App,
	tpl
) {
	var ProductView = Mn.ItemView.extend({
		template: tpl,
		tagName: 'li',
		className: '',

		events: {
			'click @ui.addToCart' : 'addToCart'
		},

		ui: {
			addToCart: '.button'
		},

		initialize: function (options) {
			this.cart = App.cart;
		},

		addToCart: function() {
			this.cart.addProductToCart(this.model);
		}
	});

	return ProductView;
});
