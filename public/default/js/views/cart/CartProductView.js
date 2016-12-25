define([
	'marionette',
	'App',
	'tpl!templates/cart/cart-product.html'
], function (
	Mn,
	App,
	tpl
) {
	var CartProductView = Mn.ItemView.extend({
		template: tpl,
		tagName: 'div',
		className: '',

		templateHelpers: function() {
			var view = this;

			return {
				img_url: '/img/products/' + view.model.get('image_url'),
				price: view.model.get('pivot').sale_price,
				inCart: view.model.get('inCart')
			}
		},

		events: {
			'click @ui.remove' : 'removeFromCart',
			'click @ui.decreaseQuantity' : 'decreaseQuantity',
			'click @ui.increaseQuantity' : 'increaseQuantity',
			'click @ui.reAddToCart' : 'reAddToCart'
		},

		modelEvents: {
			'change:quantity' : 'quantityChanged',
			'change:inCart' : 'inCartChanged'
		},

		ui: {
			'remove' : '.remove',
			'decreaseQuantity' : '.subtract',
			'increaseQuantity' : '.add',
			'quantity' : '.quantity',
			'reAddToCart': '.js-re-add-to-cart'
		},

		initialize: function (options) {
			this.listenTo(App.rootView, 'offcanvas:closed', this.onCartClose);
		},

		quantityChanged: function(model, quantity) {
			this.ui.quantity.html(quantity);
		},

		inCartChanged: function(model, inCart) {
			this.render();
		},

		removeFromCart: function(e) {
			if (e) {
				e.preventDefault();
			}

			this.model.set('inCart', false);
		},

		decreaseQuantity: function(evt) {
			evt.preventDefault();
			App.cart.remove(this.model, {}); // let cart handle this logic
		},

		increaseQuantity: function(evt) {
			evt.preventDefault();
			App.cart.add(this.model, {}); // let cart handle this logic
		},

		onCartClose: function() {
			// if this model was removed from cart by the user, time to remove the re-add to cart view and not show it again
			if (this.model.get('inCart') === false) {
				App.cart.remove(this.model, { removeAll: true }); // remove all quantities
			}
		},

		reAddToCart: function() {
			this.model.set('inCart', true);
		}
	});

	return CartProductView;
});
