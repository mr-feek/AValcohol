/**
 * Created by Feek on 3/16/16.
 */
define([
	'marionette',
	'shared/admin-retailer/js/views/LoginView',
	'views/HeaderView',
	'views/SidebarView',
	'views/VendorHomeRootView',
	'App',
	'shared/js/Brain'
], function (
	Marionette,
	LoginView,
	HeaderView,
	SidebarView,
	VendorHomeRootView,
	app,
	Brain
) {
	var Controller = Marionette.Object.extend({
		initialize: function(options) {
			this.rootView = options.rootView;
		},

		showLogin: function() {
			this.rootView.getRegion('main').show(new LoginView({
				model: Brain.retrieve('user'),
				loginSuccessCallback: function(response) {
					if (response.vendor) {
						app.vendor.hasBeenFetched = true;
						app.router.navigate('retailer/dashboard', {trigger: true});
					} else {
						alert('It seems as if you are not a vendor. If this is a mistake please contact us.');
					}
				}
			}));
		},

		showDashboard: function() {
			if (this.authorize()) {
				if (!app.vendor.hasBeenFetched) {
					app.vendor.fetch();
				}
				this.rootView.getRegion('header').show(new HeaderView({	model: app.vendor}));
				this.rootView.getRegion('main').show(new VendorHomeRootView());
				this.rootView.getRegion('offCanvas').show(new SidebarView());
				this.rootView.getRegion('sidebar').show(new SidebarView());
			}
		},

		/**
		 * If the user is not logged in, redirect to login
		 * @return boolean whether or not authorized
		 */
		authorize: function() {
			var session = Brain.retrieve('session');
			if (!session.get('token')) {
				app.router.navigate('retailer/login', {trigger: true});
				return false;
			}
			return true;
		},

		updateSidebars: function(action, name) {
			this.rootView.getRegion('sidebar').currentView.trigger(action, name);
			this.rootView.getRegion('offCanvas').currentView.trigger(action, name);
		}
	});

	return Controller;
});
