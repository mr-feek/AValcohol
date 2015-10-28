require.config({
	paths: {
		'jquery': 				'../vendor/jquery/dist/jquery',
		'underscore': 			'../vendor/underscore/underscore',
		'backbone': 			'../vendor/backbone/backbone',
		'backbone.wreqr': 		'../vendor/backbone.wreqr/lib/backbone.wreqr.min',
		'marionette': 			'../vendor/marionette/lib/backbone.marionette',
		'foundation' : 			'../vendor/foundation/js/foundation',
		'foundationEqualizer' : '../vendor/foundation/js/foundation/foundation.equalizer',
		'foundationOffCanvas': 	'../vendor/foundation/js/foundation/foundation.offcanvas',
		'slick': 				'../vendor/slick-carousel/slick/slick',
		'text': 				'../vendor/requirejs-text/text',
		'tpl': 					'../vendor/requirejs-tpl/tpl',
	},
	shim: {
		underscore: {
			exports: '_'
		},
		backbone: {
			exports: 'Backbone',
			deps: ['jquery', 'underscore']
		},
		marionette: {
			deps: ['backbone'],
			exports: 'Marionette'
		},
		foundation: {
			deps: ['jquery'],
			exports: 'Foundation'
		},
		foundationEqualizer: {
			deps: ['foundation']
		},
		foundationOffCanvas: {
			deps: ['foundation']
		}
	},
	deps: ['jquery', 'underscore', 'slick']
});

require([
	'App',
	'views/RootView',
	'controllers/Controller',
	'util/Router',
	'models/Cart',
	'foundation',
	'foundationOffCanvas'
], function (
	app,
	RootView,
	Controller,
	Router,
	Cart
) {
	$(document).foundation();

	app.on('start', function() {
		app.rootView = new RootView();

		var controller = new Controller({
			rootView: app.rootView
		});

		app.router = new Router({ controller: controller });
		app.cart = new Cart();
		app.rootView.render();
	});

	app.start();

	Backbone.history.start({
		pushState: true
	});
});