var basePath = '../../vendor/';
require.config({
	paths: {
		'jquery': 							basePath + 'jquery/dist/jquery',
		'underscore': 						basePath + 'underscore/underscore',
		'backbone': 						basePath + 'backbone/backbone',
		'backbone.wreqr': 					basePath + 'backbone.wreqr/lib/backbone.wreqr.min',
		'backboneRelational':				basePath + 'backbone-relational/backbone-relational',
		'marionette': 						basePath + 'marionette/lib/backbone.marionette',
		'foundation' : 						basePath + 'foundation-sites/dist/plugins/foundation.core',
		'foundationMediaQuery':				basePath + 'foundation-sites/dist/plugins/foundation.util.mediaQuery',
		'foundationEqualizer' : 			basePath + 'foundation-sites/dist/plugins/foundation.equalizer',
		'foundationOffCanvas': 				basePath + 'foundation-sites/dist/plugins/foundation.offcanvas',
		'foundationTooltip':				basePath + 'foundation-sites/dist/plugins/foundation.tooltip',
		'foundationTriggers':				basePath + 'foundation-sites/dist/plugins/foundation.util.triggers',
		'foundationMotion':					basePath + 'foundation-sites/dist/plugins/foundation.util.motion',
		'foundationTimerAndImageLoader': 	basePath + 'foundation-sites/dist/plugins/foundation.util.timerAndImageLoader',
		'modernizr' : 						basePath + 'modernizr/modernizr',
		'text': 							basePath + 'requirejs-text/text',
		'tpl': 								basePath + 'requirejs-tpl/tpl',
		'async': 							basePath + 'requirejs-plugins/src/async',
		'stripe':							'https://js.stripe.com/v2/?noext',
		'moment':							basePath + 'moment/moment',
		'pickaday':							basePath + 'pikaday/pikaday',
		'behaviors':						basePath + 'UsefulMarionetteViewBehaviors',
		'shared':							'../../shared',
		'nprogress':						basePath + 'nprogress/nprogress'
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
		foundationMediaQuery: {
			deps: ['foundation']
		},
		foundationMotion: {
			deps: ['foundation']
		},
		foundationTriggers: {
			deps: ['foundation']
		},
		foundationTimerAndImageLoader: {
			deps: ['foundation']
		},
		foundationEqualizer: {
			deps: ['foundationMediaQuery', 'foundationTimerAndImageLoader']
		},
		foundationOffCanvas: {
			deps: ['foundationMediaQuery', 'foundationTriggers', 'foundationMotion']
		},
		foundationTooltip: {
			deps: ['foundationMediaQuery']
		}
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