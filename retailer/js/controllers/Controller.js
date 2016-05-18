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
			if (this.authorize()) {
				this.rootView.getRegion('header').show(new HeaderView({	model: app.vendor}));
				this.rootView.getRegion('main').show(new VendorHomeRootView());
			}
		},

		/**
		 * If the user is not logged in, redirect to login
		 * @return boolean whether or not authorized
		 */
		authorize: function() {
			if (!app.session.get('token')) {
				app.router.navigate('retailer/login', {trigger: true});
				return false;
			}
			return true;
		}
	});

	return Controller;
});
