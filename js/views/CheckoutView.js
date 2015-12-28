define([
	'marionette',
	'App',
	'stripe',
	'views/CheckoutProductsView',
	'views/AddressEntryView',
	'views/BillingInfoEntryView',
	'models/Product',
	'models/User',
	'models/UserAddress',
	'tpl!templates/checkout.html'
], function (
	Mn,
	App,
	Stripe,
	CheckoutProductsView,
	AddressEntryView,
	BillingInfoEntryView,
	Product,
	User,
	UserAddress,
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

		events: {
			'click @ui.order' : 'submitOrder'
		},

		ui: {
			'order' : '.order'
		},

		regions: {
			items: '.items',
			deliveryInfo: '.delivery-info',
			billingInfo: '.billing-info'
		},

		initialize: function (options) {
			Stripe.setPublishableKey('pk_test_tMGKSXUztufdvadDjyIgyYrb'); // to do: fetch from server
		},

		onBeforeShow: function() {
			this.getRegion('items').show(new CheckoutProductsView({ collection: App.cart }));

			// if cart is empty, don't show anything else. Let checkoutproductsview emptyview handle it
			if (App.cart.length > 0) {
				this.getRegion('deliveryInfo').show(new AddressEntryView());
				this.getRegion('billingInfo').show(new BillingInfoEntryView());
			}
		},

		/**
		 * THIS SHOULD BE REFACTORED TO USE AN ORDER MODEL BUt yoLO
		 */
		submitOrder: function() {
			var view = this;

			var product = new Product({
				id: 1 // test
			});

			var user = new User({
				id: 1 // test
			});

			var userAddress = new UserAddress({
				id: 1 // test
			})

			$.ajax({
				url: '/api/order/create',
				type: 'POST',
				dataType: 'json',
				data: {
					product_id: product.get('id'),
					user_id: user.get('id'),
					address_id: userAddress.get('id')
				}
			}).done(function (result) {
				console.log('pass');
			}).fail(function (result) {
				console.log('fail');
			});
		}
	});

	return CheckoutView;
});
