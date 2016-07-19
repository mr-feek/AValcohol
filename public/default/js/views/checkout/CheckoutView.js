define([
	'marionette',
	'App',
	'stripe',
	'views/checkout/AddressEntryView',
	'views/checkout/BillingInfoEntryView',
	'views/checkout/UserInfoEntryView',
	'views/checkout/OrderReviewView',
	'views/checkout/OrderSubmittedView',
	'shared/js/util/Vent',
	'behaviors/StateManager',
	'tpl!templates/checkout/checkout.html'
], function (
	Mn,
	App,
	Stripe,
	AddressEntryView,
	BillingInfoEntryView,
	UserInfoEntryView,
	OrderReviewView,
	OrderSubmittedView,
	Vent,
	StateManager,
	tpl
) {

	/**
	 * This view is the parent view container for the checkout process. It keeps track of the
	 * current flow state
	 */
	var CheckoutView = Mn.LayoutView.extend({
		template: tpl,
		tagName: 'div',
		className: 'small-12 columns checkout',
		region: null, // initialize

		behaviors: {
			StateManager: {
				behaviorClass: StateManager
			}
		},

		events: {
			'click @ui.statuses' : '_goToView', // allow to click back to active view
		},

		ui: {
			'statusArea' : '.status-area',
			'statuses' : '.status',
			'savedView' : '.submitted', // all views that have already been saved
			'active' : '.active'
		},

		regions: {
			current: '.current'
		},

		initialize: function (options) {
			/**
			 * store the region that this view is in so that we can swap out this view
			 * with the order complete view. not ideal but need to ship this
			 */
			this.region = options.region;
			Vent.on('order:submitted', this.showOrderSubmittedView.bind(this));

			$.get('/api/stripe/key', function(response) {
				Stripe.setPublishableKey(response.key);
			}.bind(this));

			var viewsToShow = [
				new UserInfoEntryView({	parent:	this }),
				new AddressEntryView({	parent:	this }),
				new BillingInfoEntryView({	parent:	this }),
				new OrderReviewView({	parent: this })
			];

			this.triggerMethod('setViewFlow', viewsToShow);
			this.triggerMethod('setRegion', this.getRegion('current')); // is this set here?

			this.on('before:showNext', this.beforeShowNext); // triggered by behavior
		},

		/**
		 * Adds class active as well as removes the disabled class. Removes active class from previous and adds submitted
		 * @private
		 */
		_updateStatus: function(currentIndex) {
			var $status = $(this.ui.statuses[currentIndex]);
			$status.addClass('active');
			$status.removeClass('disabled');

			var $prevStatus = $(this.ui.statuses[currentIndex - 1]);
			$prevStatus.removeClass('active');
			$prevStatus.addClass('submitted');
		},

		// handle any logic before behavior switches views.
		beforeShowNext: function(currentIndex) {
			this._updateStatus(currentIndex);
		},

		/**
		 * Removes the current view from being active
		 * @private
		 */
		_removeActiveClass: function(currentIndex) {
			if (currentIndex) {
				var $status = $(this.ui.statuses[currentIndex]);
				$status.removeClass('active');
				return;
			}

			this.ui.statuses.removeClass('active');
		},

		/**
		 * replaces THIS view with the order submitted view
		 * @param order
		 */
		showOrderSubmittedView: function(order) {
			this.region.show(new OrderSubmittedView({ model: order }));
		},

		/**
		 * Function to go to the clicked view (from the status thing)
		 * @param evt
		 * @private
		 */
		_goToView: function(evt) {
			if ($(evt.currentTarget).hasClass('disabled')) {
				return;
			}

			this._removeActiveClass();
			var indexToShow = null;

			_.each(this.ui.statuses, function(status, index) {
				if (evt.currentTarget === status) {
					indexToShow = index;
				}
			}.bind(this));

			// call behavior
			this.triggerMethod('goToIndex', indexToShow);

			this.beforeShowNext(indexToShow); // hack to update new active class
		},

		goToViewBasedOnIndex(indexToShow) {
			this._removeActiveClass();
			// call behavior
			this.triggerMethod('goToIndex', indexToShow);

			this.beforeShowNext(indexToShow); // hack to update new active class
		},

		goToViewBasedOnName(name) {
			var indexToShow;
			
			switch(name) {
				case 'user':
					indexToShow = 0;
					break;
				case  'address':
					indexToShow = 1;
					break;
				case 'billing':
					indexToShow = 2;
					break;
			}

			this._removeActiveClass();
			// call behavior
			this.triggerMethod('goToIndex', indexToShow);

			this.beforeShowNext(indexToShow); // hack to update new active class
		}
	});

	return CheckoutView;
});
