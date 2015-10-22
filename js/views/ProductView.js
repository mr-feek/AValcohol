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

		attributes: {
			'data-equalizer-watch' : ''
		},

		events: {
			'click @ui.addToCart' : 'addToCart'
		},

		ui: {
			addToCart: '.button'
		},

		templateHelpers: function() {
			var view = this;
			return {
				img_url: '/img/products/' + view.model.get('image_url')
			}
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
