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
			'payButton' : '.button.order',
			'note' : '.note'
		},

		regions: {
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
			this.showActiveView();
		},

		showActiveView: function() {
			this.updateStatus();
			// we are preventing destroy here, so remember to clean up later
			this.getRegion('active').show(this.viewFlow[this.currentIndex], {	preventDestroy:	true	});
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
			// get note
			var note = this.ui.note.val();

			// create order
			var order = Order.findOrCreate({
				products: App.cart,
				user: App.user,
				address: App.user.get('address'),
				stripe_token: App.user.get('token'),
				note: note
			});

			order.save().done(function (result) {
				console.log('pass');
			}).fail(function (result) {
				console.log('fail');
			});
		}
	});

	return CheckoutView;
});
