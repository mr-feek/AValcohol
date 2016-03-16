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
		}
	});

	return Router;
});
