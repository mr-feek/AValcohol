/**
 * Created by Feek on 3/16/16.
 */
define([
	'marionette',
	'views/LoginView',
	'views/HeaderView',
	'views/VendorHomeRootView',
	'App'
], function (
	Marionette,
	LoginView,
	HeaderView,
	VendorHomeRootView,
	app
) {
	var Controller = Marionette.Object.extend({
		initialize: function(options) {
			this.rootView = options.rootView;
		},

		showLogin: function() {
			// if logged in already, just show the dashboard instead
			this.rootView.getRegion('main').show(new LoginView({	model: app.vendor }));
		},

		showDashboard: function() {
			this.authorize();
			this.rootView.getRegion('header').show(new HeaderView({	model: app.vendor}));
			this.rootView.getRegion('main').show(new VendorHomeRootView());
		},

		authorize: function() {
			// if the model isn't logged in, redirect to login
		}
	});

	return Controller;
});
