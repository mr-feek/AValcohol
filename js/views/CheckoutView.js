define([
	'marionette',
	'App',
	'views/CheckoutProductsView',
	'views/AddressEntryView',
	'tpl!templates/checkout.html'
], function (
	Mn,
	App,
	CheckoutProductsView,
	AddressEntryView,
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

		regions: {
			items: '.items',
			address: '.address'
		},

		initialize: function (options) {
		},

		onBeforeShow: function() {
			this.getRegion('items').show(new CheckoutProductsView({ collection: App.cart }));

			// if cart is empty, don't show anything else. Let checkoutproductsview emptyview handle it
			if (App.cart.length > 0) {
				this.getRegion('address').show(new AddressEntryView());
			}

		}
	});

	return CheckoutView;
});
