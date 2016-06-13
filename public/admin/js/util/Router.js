/**
 * Created by Feek on 3/16/16.
 */
define([
	'marionette'
], function (Marionette) {

	var Router = Marionette.AppRouter.extend({
		initialize: function(options) {
			this.controller = options.controller;
		},

		appRoutes: {
			'admin/' 				: 'showLogin',
			'admin/login' 			: 'showLogin',
			'admin/dashboard' 		: 'showStatView',
			'admin/dashboard/stats' : 'showStatView',
			'admin/dashboard/ready' : 'showReadyOrders',
			'admin/dashboard/out' 	: 'showOrdersOutForDelivery',
			'admin/dashboard/all' 	: 'showAllOrders',
			'admin/dashboard/factory': 'showFactory'
		}
	});

	return Router;
});
