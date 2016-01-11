define([
	'marionette',
	'App',
	'stripe',
	'views/checkout/CheckoutProductsView',
	'views/checkout/AddressEntryView',
	'views/checkout/BillingInfoEntryView',
	'views/checkout/UserInfoEntryView',
	'models/Product',
	'models/User',
	'models/UserAddress',
	'models/Order',
	'tpl!templates/checkout/checkout.html'
], function (
	Mn,
	App,
	Stripe,
	CheckoutProductsView,
	AddressEntryView,
	BillingInfoEntryView,
	UserInfoEntryView,
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
		currentIndex: 0,
		viewFlow: [], // populated in initialize

		templateHelpers: function() {
			return {
				cart: App.cart
			}
		},

		events: {
			'click @ui.order' : 'getStripeToken',
			'click @ui.savedView' : 'goToView'
		},

		ui: {
			'statusArea' : '.status-area',
			'statuses' : '.status',
			'savedView' : '.submitted',
			'order' : '.order',
			'billingForm' : '.billing-info',
			'payButton' : '.button.order'
		},

		regions: {
			items: '.items',
			active: '.active'
		},

		initialize: function (options) {
			$.get('/api/stripe/key', function(response) {
				Stripe.setPublishableKey(response.key);
			}.bind(this));

			this.viewFlow.push(
				new UserInfoEntryView({	parent:	this }),
				new AddressEntryView({	parent:	this }),
				new BillingInfoEntryView({	parent:	this })
			);
		},

		onBeforeShow: function() {
			this.getRegion('items').show(new CheckoutProductsView({ collection: App.cart }));
			this.showActiveView();
		},

		showActiveView: function() {
			this.updateStatus();
			// we are preventing destroy here, so remember to clean up later
			this.getRegion('active').show(this.viewFlow[this.currentIndex], {	preventDestroy:	true	});
		},

		disablePayButton() {
			this.ui.payButton.addClass('disabled');
		},

		enablePayButton() {
			this.ui.payButton.removeClass('disabled');
		},

		showNext: function() {
			this.currentIndex++;
			this.showActiveView();
		},

		updateStatus: function() {
			var $status = $(this.ui.statuses[this.currentIndex]);
			$status.addClass('submitted');
			$status.removeClass('disabled');
		},

		goToView: function(evt) {
			_.each(this.ui.statuses, function(status, index) {
				if (evt.target === status) {
					this.currentIndex = index;
				}
			}.bind(this));

			this.showActiveView();
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
