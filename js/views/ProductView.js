define([
	'marionette',
	'../collections/Cart',
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
		className: 'product-wrapper',

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
		},

		addToCart: function() {
			App.cart.push(this.model);
			App.rootView.openOffCanvas(); // show the cart
		}
	});

	return ProductView;
});
