var basePath = '../../vendor/';
require.config({
	paths: {
		'jquery': 				basePath + 'jquery/dist/jquery',
		'underscore': 			basePath + 'underscore/underscore',
		'backbone': 			basePath + 'backbone/backbone',
		'backbone.wreqr': 		basePath + 'backbone.wreqr/lib/backbone.wreqr.min',
		'backboneRelational':	basePath + 'backbone-relational/backbone-relational',
		'marionette': 			basePath + 'marionette/lib/backbone.marionette',
		'foundation' : 			basePath + 'foundation/js/foundation',
		'foundationEqualizer' : basePath + 'foundation/js/foundation/foundation.equalizer',
		'foundationOffCanvas': 	basePath + 'foundation/js/foundation/foundation.offcanvas',
		'modernizr' : 			basePath + 'modernizr/modernizr',
		'text': 				basePath + 'requirejs-text/text',
		'tpl': 					basePath + 'requirejs-tpl/tpl',
		'async': 				basePath + 'requirejs-plugins/src/async',
		'stripe':				'https://js.stripe.com/v2/?noext',
		'moment':				basePath + 'moment/moment',
		'pickaday':				basePath + 'pikaday/pikaday',
		'behaviors':			basePath + 'UsefulMarionetteViewBehaviors',
		'shared':				'../../shared'
	},
	shim: {
		underscore: {
			exports: '_'
		},
		backbone: {
			exports: 'Backbone',
			deps: ['jquery', 'underscore']
		},
		backboneRelational: {
			exports: 'BackboneRelational',
			deps: ['backbone'],
		},
		marionette: {
			deps: ['backbone'],
			exports: 'Marionette'
		},
		foundation: {
			deps: ['jquery', 'modernizr'],
			exports: 'Foundation'
		},
		foundationEqualizer: {
			deps: ['foundation']
		},
		foundationOffCanvas: {
			deps: ['foundation']
		},
	},
	deps: ['jquery', 'underscore']
});

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

	app.on('start', function() {
		app.rootView = new RootView();

		app.cart = new Cart();
		app.user = User.findOrCreate({});
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

		// freeze scrolling of main content when offcanvas is open
		$(document)
			.on('open.fndtn.offcanvas', '[data-offcanvas]', function() {
				$('#all-wrapper').css({
					'overflow' : 'hidden',
					'height' : '100vh'
				});
			})
			.on('close.fndtn.offcanvas', '[data-offcanvas]', function() {
				$('#all-wrapper').css({
					'overflow' : 'initial',
					'height' : 'initial'
				});
			})
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