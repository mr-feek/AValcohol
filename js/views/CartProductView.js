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

		removeFromCart: function() {
			var view = this;
			this.$el.fadeOut('fast', function() {
				App.cart.remove(view.model);
			});
		}
	});

	return CartProductView;
});
