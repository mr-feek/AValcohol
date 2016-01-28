/**
 * Created by Feek on 10/13/15.
 */

define([
	'marionette',
	'views/landing/HeaderView',
	'views/landing/MVPHomeView',
	'views/user-home/UserHomeView',
	'views/checkout/CheckoutView'
], function (
	Marionette,
	HeaderView,
	MVPHomeView,
	UserHomeView,
	CheckoutView
) {
	var Controller = Marionette.Object.extend({
		rootView: null,

		initialize: function(options) {
			this.rootView = options.rootView;
		},

		showHome: function() {
			// theres a race condition here i think...
			var region = this.rootView.getRegion('main');
			if (region._ensureElement()) {
				region.show(new MVPHomeView());
			} else {
				// try again
				console.err('delaying and trying again..');
				_.delay(this.showHome, 100);
			}
		},

		showUserHome: function(endpoint) {
			var region = this.rootView.getRegion('main');
			// if we already have a userhomeview rendered, just swap out the products view
			if (!region.currentView || !(region.currentView instanceof UserHomeView)) {
				region.show(new UserHomeView({ endpoint: endpoint }));
			} else {
				region.currentView.showDifferentProductView(endpoint);
			}
		},

		showCheckout: function() {
			var region = this.rootView.getRegion('main');
			region.show(new CheckoutView({	region: region }));
		}
	});

	return Controller;
});
