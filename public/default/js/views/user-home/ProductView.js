define([
	'marionette',
	'collections/Cart',
	'App',
	'tpl!templates/user-home/product.html',
	'foundationTooltip'
], function (
	Mn,
	Cart,
	app,
	tpl
) {
	var ProductView = Mn.ItemView.extend({
		template: tpl,
		tagName: 'li',
		className: 'product column',
		attributes: {
			'data-equalizer-watch' : ''
		},

		events: {
			'click @ui.addToCart' : 'addToCart'
		},

		modelEvents: {
			'change:inCart' : 'inCartChange',
			'change:quantity' : 'quantityChange'
		},

		ui: {
			addToCart: '.button',
			quantity: '.quantity'
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

		/**
		 * update the view to reflect whether or not this model is in the cart
		 * @param model
		 * @param inCart
		 */
		inCartChange: function(model, inCart) {
			if (inCart) {
				this.$el.addClass('in-cart');
				return;
			}
			
			this.$el.removeClass('in-cart');
		},

		quantityChange: function(model, quantity) {
			if (quantity > 0) {
				this.$el.addClass('in-cart');
				this.ui.quantity.html(quantity);
			} else {
				this.$el.removeClass('in-cart');
			}
		},

		addToCart: function(e) {
			e.preventDefault();
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

			app.rootView.getRegion('main').currentView.checkIfShouldOpenCart(e);
		},

		onShow: function() {
			if (app.config.get('isClosed')) {
				new Foundation.Tooltip(this.ui.addToCart.find('.has-tip'));
			}
		},

		/**
		 * add in-cart class to show whether or not an item is currently in the cart
		 *
		 * add disabled class to add to cart button if store is closed
		 */
		onRender: function() {
			if (this.model.get('inCart')) {
				this.$el.addClass('in-cart');
				this.ui.quantity.html(this.model.get('quantity'));
			}

			if (app.config.get('isClosed')) {
				this.ui.addToCart.addClass('disabled');
				this.ui.addToCart.html(
					'<span data-tooltip aria-haspopup="true" class="has-tip" data-disable-hover="false" tabindex="1" title="Sorry, our store is currently closed.">Add To Cart</span>'
				);
			}
		}
	});

	return ProductView;
});
