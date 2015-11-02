define([
	'marionette',
	'App',
	'views/CheckoutProductsView',
	'views/AddressEntryView',
	'views/BillingInfoEntryView',
	'tpl!templates/checkout.html'
], function (
	Mn,
	App,
	CheckoutProductsView,
	AddressEntryView,
	BillingInfoEntryView,
	tpl
) {
	var CheckoutView = Mn.LayoutView.extend({
		template: tpl,
		tagName: 'div',
		className: 'small-12 columns',

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
			deliveryInfo: '.delivery-info',
			billingInfo: '.billing-info'
		},

		initialize: function (options) {
		},

		onBeforeShow: function() {
			this.getRegion('items').show(new CheckoutProductsView({ collection: App.cart }));

			// if cart is empty, don't show anything else. Let checkoutproductsview emptyview handle it
			//if (App.cart.length > 0) {
				this.getRegion('deliveryInfo').show(new AddressEntryView());
				this.getRegion('billingInfo').show(new BillingInfoEntryView());
			//}

		}
	});

	return CheckoutView;
});
