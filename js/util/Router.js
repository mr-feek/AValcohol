define([
	'marionette'
], function (Marionette) {

	var Router = Marionette.AppRouter.extend({
		initialize: function(options) {
			this.controller = options.controller;
		},

		appRoutes: {
			'' : 'showHome',
			'home' : 'showUserHome'
		}
	});

	return Router;
});