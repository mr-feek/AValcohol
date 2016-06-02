/**
 * Created by Feek on 3/16/16.
 */
define([
	'marionette',
	'views/LoginView',
	'views/HeaderView',
	'views/HomeRootView',
	'views/ReadyOrdersView',
	'views/OrdersOutForDeliveryView',
	'views/AllOrdersView'
], function (
	Marionette,
	LoginView,
	HeaderView,
	HomeRootView,
	ReadyOrdersView,
	OrdersOutForDeliveryView,
	AllOrdersView
) {
	var Controller = Marionette.Object.extend({
		initialize: function(options) {
			this.rootView = options.rootView;
		},

		showLogin: function() {
			// if logged in already, just show the dashboard instead
			this.rootView.getRegion('main').show(new LoginView());
		},

		_showDashboard: function() {
			this._authorize();
			this.rootView.getRegion('header').show(this._getHeaderView());
			this.rootView.getRegion('main').show(this._getHomeView());
		},

		showOrdersOutForDelivery: function() {
			this._showDashboard();
			this._getHomeView().getRegion('main').show(new OrdersOutForDeliveryView());
			this._getSidebarView().trigger('showing', 'out');
		},

		showReadyOrders: function() {
			this._showDashboard();
			this._getHomeView().getRegion('main').show(new ReadyOrdersView());
			this._getSidebarView().trigger('showing', 'ready');
		},

		showAllOrders: function() {
			this._showDashboard();
			this._getHomeView().getRegion('main').show(new AllOrdersView());
			this._getSidebarView().trigger('showing', 'all');
		},

		_authorize: function() {
			// if the model isn't logged in, redirect to login
		},

		_getHomeView: function() {
			if (!this.home) {
				this.home = new HomeRootView();
			}
			return this.home;
		},

		_getSidebarView: function() {
			if (!this.sidebarView) {
				this.sidebarView = this._getHomeView().getRegion('sidebar').currentView;
			}

			return this.sidebarView;
		},

		_getHeaderView: function() {
			if (!this.headerView) {
				this.headerView = new HeaderView();
			}
			return this.headerView;
		},
	});

	return Controller;
});
