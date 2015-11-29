define([
	'marionette',
	'App',
	'tpl!templates/cart-product.html'
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

				total: function() {
					var total = view.model.get('price') * view.model.get('quantity');
					return Number(total).toFixed(2);
				}
			}
		},

		events: {
			'click @ui.remove' : 'removeFromCart',
			'click @ui.decreaseQuantity' : 'decreaseQuantity',
			'click @ui.increaseQuantity' : 'increaseQuantity'
		},

		ui: {
			'remove' : '.remove',
			'decreaseQuantity' : '.subtract',
			'increaseQuantity' : '.add'
		},

		initialize: function (options) {
		},

		/**
		 * If there is more than one quantity, it will subtract one quantity instead of fully removing
		 * from cart
		 * @param e
		 */
		/*
		removeFromCart: function(e) {
			e.preventDefault();
			var view = this;

			var quantity = this.model.get('quantity');
			if (quantity > 1) {
				this.model.set('quantity', quantity - 1);
			} else {
				this.$el.fadeOut('fast', function() {
					App.cart.remove(view.model);
				});
			}
		}
		*/

		removeFromCart: function(e) {
			// may have been called internally
			if (e) {
				e.preventDefault();
			}

			var view = this;

			this.$el.fadeOut('fast', function() {
				App.cart.remove(view.model);
			});
		},

		decreaseQuantity: function() {
			var quantity = this.model.get('quantity');
			if (quantity > 1) {
				this.model.set('quantity', quantity - 1);
			} else {
				// 0 quantity, remove from cart
				this.removeFromCart();
			}
		},

		increaseQuantity: function() {
			var quantity = this.model.get('quantity');
			this.model.set('quantity', quantity + 1);
		}
	});

	return CartProductView;
});
