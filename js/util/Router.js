define([
	'marionette'
], function (Marionette) {

	var Router = Marionette.AppRouter.extend({
		initialize: function(options) {
			this.controller = options.controller;
		},

		appRoutes: {
			'' : 'showHome',
			'home' : 'showUserHome',
			'home/:endpoint' : 'showUserHome',
			'checkout' : 'showCheckout'
		}
	});

	return Router;
});
