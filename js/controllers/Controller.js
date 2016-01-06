/**
 * Created by Feek on 10/13/15.
 */

define([
	'marionette',
	'views/HeaderView',
	'views/MVPHomeView',
	'views/UserHomeView',
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
			this.rootView.getRegion('main').show(new MVPHomeView());
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
			this.rootView.getRegion('main').show(new CheckoutView());
		}
	});

	return Controller;
});
