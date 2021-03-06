/**
 * Created by Feek on 3/16/16.
 */
define([
	'marionette',
	'shared/admin-retailer/js/views/LoginView',
	'views/HeaderView',
	'views/SidebarView',
	'views/HomeRootView',
	'views/StatView',
	'views/ReadyOrdersView',
	'views/OrdersOutForDeliveryView',
	'views/AllOrdersView',
	'views/VendorsView',
	'views/VendorHoursView',
	'views/factory/FactoryView',
	'App',
	'shared/js/Brain'
], function (
	Marionette,
	LoginView,
	HeaderView,
	SidebarView,
	HomeRootView,
	StatView,
	ReadyOrdersView,
	OrdersOutForDeliveryView,
	AllOrdersView,
	VendorsView,
	VendorHoursView,
	FactoryView,
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
					if (Brain.retrieve('user').isAdmin()) {
						app.router.navigate('admin/dashboard', {trigger: true});
					} else {
						alert('it seems as if you are not an admin. If this is incorrect, please let someone know.');
					}
				}
			}));
		},

		_showDashboard: function() {
			if (this.authorize()) {
				this.rootView.getRegion('header').show(this._getHeaderView());
				this.rootView.getRegion('main').show(this._getHomeView());
				this.rootView.getRegion('sidebar').show(new SidebarView());
				this.rootView.getRegion('offCanvas').show(new SidebarView());
			}
		},

		// this class needs to be refactored so badly



		showStatView: function() {
			this._showDashboard();
			this._getHomeView().getRegion('main').show(new StatView());
			this.updateSidebars('showing', 'stat');
		},

		showOrdersOutForDelivery: function() {
			this._showDashboard();
			this._getHomeView().getRegion('main').show(new OrdersOutForDeliveryView());
			this.updateSidebars('showing', 'out');
		},

		showReadyOrders: function() {
			this._showDashboard();
			this._getHomeView().getRegion('main').show(new ReadyOrdersView());
			this.updateSidebars('showing', 'ready');
		},

		showAllOrders: function() {
			this._showDashboard();
			this._getHomeView().getRegion('main').show(new AllOrdersView());
			this.updateSidebars('showing', 'all');
		},

		showVendors: function() {
			this._showDashboard();
			this._getHomeView().getRegion('main').show(new VendorsView());
			this.updateSidebars('showing', 'vendor');
		},

		showFactory: function() {
			this._showDashboard();
			this._getHomeView().getRegion('main').show(new FactoryView());
			this.updateSidebars('showing', 'factory');
		},

		/**
		 * If the user is not logged in, redirect to login
		 * @return boolean whether or not authorized
		 */
		authorize: function() {
			var session = Brain.retrieve('session');
			
			if (!session.get('token')) {
				app.router.navigate('admin/login', {trigger: true});
				return false;
			}
			return true;
		},

		_getHomeView: function() {
			if (!this.home || this.home.isDestroyed) {
				this.home = new HomeRootView();
			}
			return this.home;
		},

		_getHeaderView: function() {
			if (!this.headerView || this.headerView.isDestroyed) {
				this.headerView = new HeaderView();
			}
			return this.headerView;
		},

		updateSidebars: function(action, name) {
			this.rootView.getRegion('sidebar').currentView.trigger(action, name);
			this.rootView.getRegion('offCanvas').currentView.trigger(action, name);
		},
		
		showVendorHours: function(id) {
			this._showDashboard();
			this._getHomeView().getRegion('main').show(new VendorHoursView({
				id: id
			}));
		}
	});

	return Controller;
});
