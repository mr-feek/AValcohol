define([
	'marionette',
	'collections/Cart',
	'App',
	'tpl!templates/user-home/product.html'
], function (
	Mn,
	Cart,
	app,
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
				img_url: '/img/products/' + view.model.get('image_url'),
				price: view.model.get('pivot').sale_price,
				contains: view.model.get('contains'),
				ounces: view.model.get('ounces'),
				container: view.model.get('container')
			}
		},

		initialize: function (options) {
		},

		addToCart: function() {
			if (app.config.get('isClosed')) {
				return;
			}
			
			this.model.set('inCart', true);

			/**
			 * Delaying this because app.cart.push is expensive and slows down the animation of
			 * showing the cart. not ideal but it'll do for now
			 */
			_.delay(function() {
				app.cart.push(this.model);
			}.bind(this), 300);

			app.rootView.getRegion('main').currentView.openCart();
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
