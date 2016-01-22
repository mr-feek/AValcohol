define([
	'marionette',
	'collections/Cart',
	'App',
	'tpl!templates/user-home/product.html'
], function (
	Mn,
	Cart,
	App,
	tpl
) {
	var ProductView = Mn.ItemView.extend({
		template: tpl,
		tagName: 'li',
		className: 'product',

		attributes: {
			'data-equalizer-watch' : ''
		},

		events: {
			'click @ui.addToCart' : 'addToCart'
		},

		modelEvents: {
			'change:inCart' : 'inCartChange'
		},

		/**
		 * update the view to reflect whether or not this model is in the cart
		 * @param model
		 * @param inCart
		 */
		inCartChange: function(model, inCart) {
			if (inCart) {
				this.$el.addClass('in-cart');
			} else {
				this.$el.removeClass('in-cart');
			}
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
			this.model.set('inCart', true);
			App.cart.push(this.model);
			App.rootView.openOffCanvas(); // show the cart
		},

		/**
		 * add in-cart clsss to show whether or not an item is currently in the cart
		 */
		onRender: function() {
			if (this.model.get('inCart')) {
				this.$el.addClass('in-cart');
			}
		}
	});

	return ProductView;
});
