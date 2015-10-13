define([
	'marionette',
	'../controllers/Controller'
], function (Marionette, Controller) {
	var controller = new Controller();

	var Router = new Marionette.AppRouter({
		controller: controller,

		appRoutes: {
			'home' : 'showUserHome'
		}
	});

	return Router;
});
