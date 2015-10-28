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

			}
		},

		events: {
			'click @ui.remove' : 'removeFromCart'
		},

		ui: {
			'remove' : '.remove'
		},

		initialize: function (options) { },

		/**
		 * If there is more than one quantity, it will subtract one quantity instead of fully removing
		 * from cart
		 * @param e
		 */
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
	});

	return CartProductView;
});
