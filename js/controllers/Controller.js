/**
 * Created by Feek on 10/13/15.
 */

define([
	'marionette',
	'views/HeaderView',
	'views/MVPHomeView',
	'views/UserHomeView',
	'views/CheckoutView'
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
			this.rootView.getRegion('main').show(new UserHomeView({ endpoint: endpoint }));
		},

		showCheckout: function() {
			this.rootView.getRegion('main').show(new CheckoutView());
		}
	});

	return Controller;
});
