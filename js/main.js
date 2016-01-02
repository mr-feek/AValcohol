require.config({
	paths: {
		'jquery': 				'../vendor/jquery/dist/jquery',
		'underscore': 			'../vendor/underscore/underscore',
		'backbone': 			'../vendor/backbone/backbone',
		'backbone.wreqr': 		'../vendor/backbone.wreqr/lib/backbone.wreqr.min',
		'backboneRelational':	'../vendor/backbone-relational/backbone-relational',
		'marionette': 			'../vendor/marionette/lib/backbone.marionette',
		'foundation' : 			'../vendor/foundation/js/foundation',
		'foundationEqualizer' : '../vendor/foundation/js/foundation/foundation.equalizer',
		'foundationOffCanvas': 	'../vendor/foundation/js/foundation/foundation.offcanvas',
		'modernizr' : 			'../vendor/modernizr/modernizr',
		'slick': 				'../vendor/slick-carousel/slick/slick',
		'text': 				'../vendor/requirejs-text/text',
		'tpl': 					'../vendor/requirejs-tpl/tpl',
		'async': 				'../vendor/requirejs-plugins/src/async',
		'stripe':				'https://js.stripe.com/v2/?noext'
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
	deps: ['jquery', 'underscore', 'slick']
});

require([
	'App',
	'views/RootView',
	'controllers/Controller',
	'util/Router',
	'collections/Cart',
	'models/User',
	'foundation',
	'foundationOffCanvas'
], function (
	app,
	RootView,
	Controller,
	Router,
	Cart,
	User
) {
	$(document).foundation();

	app.on('start', function() {
		app.rootView = new RootView();

		var controller = new Controller({
			rootView: app.rootView
		});

		app.router = new Router({ controller: controller });
		app.cart = new Cart();
		app.user = User.findOrCreate({});
		app.rootView.render();
	});

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
});