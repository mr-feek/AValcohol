/**
 * Created by Feek on 10/13/15.
 */

define([
	'marionette',
	'views/landing/HeaderView',
	'views/landing/MVPHomeView',
	'views/user-home/UserHomeView',
	'views/checkout/CheckoutView',
	'App'
], function (
	Marionette,
	HeaderView,
	MVPHomeView,
	UserHomeView,
	CheckoutView,
	app
) {
	var Controller = Marionette.Object.extend({
		rootView: null,

		initialize: function(options) {
			this.rootView = options.rootView;

			_.bindAll(this, 'showHome', 'showCheckout');
		},

		showHome: function() {
			var region = this.rootView.getRegion('main');
			region.show(new MVPHomeView());
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
			if (app.cart.length == 0) {
				alert('You don\'t have any items in your cart! Let\'s change that.');
				app.router.navigate('', {trigger: true});
				return;
			}
			var region = this.rootView.getRegion('main');
			region.show(new CheckoutView({	region: region }));
		}
	});

	return Controller;
});
