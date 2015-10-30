define([
	'marionette',
	'App',
	'tpl!templates/checkout.html'
], function (
	Mn,
	App,
	tpl
) {
	var CheckoutView = Mn.LayoutView.extend({
		template: tpl,
		tagName: 'div',
		className: '',

		templateHelpers: function() {
			var view = this;

			return {
				cart: App.cart
			}
		},

		events: {},

		ui: {},

		initialize: function (options) {
		}
	});

	return CheckoutView;
});
