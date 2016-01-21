define([
	'marionette',
	'App',
	'stripe',
	'views/checkout/AddressEntryView',
	'views/checkout/BillingInfoEntryView',
	'views/checkout/UserInfoEntryView',
	'views/checkout/OrderReviewView',
	'tpl!templates/checkout/checkout.html'
], function (
	Mn,
	App,
	Stripe,
	AddressEntryView,
	BillingInfoEntryView,
	UserInfoEntryView,
	OrderReviewView,
	tpl
) {

	/**
	 * This view is the parent view container for the checkout process. It keeps track of the
	 * current flow state
	 */
	var CheckoutView = Mn.LayoutView.extend({
		template: tpl,
		tagName: 'div',
		className: 'small-12 columns',
		currentIndex: 0,
		viewFlow: [], // populated in initialize

		events: {
			'click @ui.savedView' : '_goToView'
		},

		ui: {
			'statusArea' : '.status-area',
			'statuses' : '.status',
			'savedView' : '.submitted'
		},

		regions: {
			current: '.current'
		},

		initialize: function (options) {
			$.get('/api/stripe/key', function(response) {
				Stripe.setPublishableKey(response.key);
			}.bind(this));

			this.viewFlow.push(
				new UserInfoEntryView({	parent:	this }),
				new AddressEntryView({	parent:	this }),
				new BillingInfoEntryView({	parent:	this }),
				new OrderReviewView({	parent: this })
			);
		},

		onBeforeShow: function() {
			this._showCurrentView();
		},

		/**
		 * Updates the current status and shows the view at the current index
		 * @private
		 */
		_showCurrentView: function() {
			this._updateStatus();
			// we are preventing destroy here, so remember to clean up later
			this.getRegion('current').show(this.viewFlow[this.currentIndex], {	preventDestroy:	true	});
		},

		/**
		 * Increments the current index and shows the next view, also updating the active class
		 */
		showNext: function() {
			this._removeActiveClass();
			this.currentIndex++;
			this._showCurrentView();
		},

		/**
		 * Adds class submitted and active as well as removes the disabled class. Does NOT remove an active class
		 * from the previous state, so do so yourself
		 * @private
		 */
		_updateStatus: function() {
			var $status = $(this.ui.statuses[this.currentIndex]);
			$status.addClass('submitted');
			$status.addClass('active');
			$status.removeClass('disabled');
		},

		/**
		 * Removes the current view from being active
		 * @private
		 */
		_removeActiveClass: function() {
			var $status = $(this.ui.statuses[this.currentIndex]);
			$status.removeClass('active');
		},

		/**
		 * Helper function to switch which index is being shown. Updates the active class
		 * @param index
		 */
		goToIndex: function(index) {
			this._removeActiveClass();
			this.currentIndex = index;
			this._showCurrentView();
		},

		/**
		 * Function to go to the clicked view (from the status thing)
		 * @param evt
		 * @private
		 */
		_goToView: function(evt) {
			this._removeActiveClass();
			_.each(this.ui.statuses, function(status, index) {
				if (evt.currentTarget === status) {
					this.currentIndex = index;
				}
			}.bind(this));

			this._showCurrentView();
		}
	});

	return CheckoutView;
});
