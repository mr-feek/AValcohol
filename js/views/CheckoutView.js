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
	'models/Order',
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
	Order,
	tpl
) {
	var CheckoutView = Mn.LayoutView.extend({
		template: tpl,
		tagName: 'div',
		className: 'small-12 columns',

		templateHelpers: function() {
			return {
				cart: App.cart
			}
		},

		events: {
			'click @ui.order' : 'getStripeToken'
		},

		ui: {
			'order' : '.order',
			'billingForm' : '.billing-info',
			'payButton' : '.button.order'
		},

		regions: {
			items: '.items',
			deliveryInfo: '.delivery-info',
			billingInfo: '.billing-info'
		},

		initialize: function (options) {
			$.get('/api/stripe/key', function(response) {
				Stripe.setPublishableKey(response.key);
				this.enablePayButton();
			}.bind(this));
		},

		onBeforeShow: function() {
			this.getRegion('items').show(new CheckoutProductsView({ collection: App.cart }));

			// if cart is empty, don't show anything else. Let checkoutproductsview emptyview handle it
			//if (App.cart.length > 0) {
				this.getRegion('deliveryInfo').show(new AddressEntryView());
				this.getRegion('billingInfo').show(new BillingInfoEntryView());
			//}
		},

		disablePayButton() {
			this.ui.payButton.addClass('disabled');
		},

		enablePayButton() {
			this.ui.payButton.removeClass('disabled');
		},

		/**
		 * creates a new order model and saves it to the backend
		 * @param token verified stripe token
		 */
		submitOrder: function(token) {
			var order = Order.findOrCreate({
				products: App.cart,
				user: App.user,
				address: App.user.get('address'),
				stripe_token: token
			});

			order.save().done(function (result) {
				console.log('pass');
			}).fail(function (result) {
				console.log('fail');
			});
		},

		/**
		 * Stops the form from being submitted and sends the required details to stripe to authorize a token
		 * disables the submit button
		 * Calls stripeResponseHandler
		 * @param evt
		 */
		getStripeToken: function(evt) {
			evt.preventDefault();

			if (this.ui.payButton.hasClass('disabled')) {
				return;
			}

			var $form = this.ui.billingForm;

			// Disable the submit button to prevent repeated clicks
			this.disablePayButton();

			Stripe.card.createToken($form.context, this.stripeResponseHandler.bind(this));
		},

		/**
		 * handler for getting the stripe token
		 * @param status
		 * @param response
		 */
		stripeResponseHandler: function(status, response) {
			console.log('stripe response handler');
			var $form = this.ui.billingForm;

			if (response.error) {
				// Show the errors on the form
				$form.find('.payment-errors').text(response.error.message);
				$form.find('button').prop('disabled', false);
			} else {
				// response contains id and card, which contains additional card details
				var token = response.id;
				this.submitOrder(token);
			}
		}
	});

	return CheckoutView;
});
