require([
	'App',
	'backbone',
	'views/RootView',
	'controllers/Controller',
	'util/Router',
	'collections/Cart',
	'shared/js/models/User',
	'shared/js/models/Config',
	'foundation',
	'foundationOffCanvas'
], function (
	app,
	Backbone,
	RootView,
	Controller,
	Router,
	Cart,
	User,
	Config
) {
	$(document).foundation();

	// add animatecss function to jquery
	$.fn.extend({
		animateCss: function (animationName, options) {
			var options = options || {};
			var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
			$(this).addClass('animated ' + animationName).one(animationEnd, function() {
				if (options.removeAnimateClass) {
					$(this).removeClass('animated ' + animationName);
				}

				if (options.callback) {
					options.callback();
				}
			});
		}
	});

	app.on('start', function() {
		app.rootView = new RootView();

		app.cart = new Cart();

		var options = {
			useStorage: true // save / load address from storage
		}
		
		app.user = User.findOrCreate({}, options);

		app.rootView.render();

		var controller = new Controller({
			rootView: app.rootView
		});

		app.router = new Router({
			controller: controller
		});
	});

	// screw it, we're waiting for config to fetch before starting app
	app.config = new Config();
	app.config.fetch().done(function() {
		app.start();

		Backbone.history.start({
			pushState: true
		});
	}).error(function(response) {
		if (response.status === 503) {
			// api is down for maintenance
			$('body').html(
				'<div style="text-align:center;">' +
				'<h1>We Are Currently Down For Maintenance.</h1>' +
				'<p>Please check back soon - Aqua Vitae</p>' +
				'</div>'
			);
		}
	});
});