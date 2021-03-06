/**
 * Created by Feek on 3/16/16.
 */
var basePath = '../../vendor/';

require.config({
	paths: {
		'jquery': 				'../../vendor/jquery/dist/jquery',
		'underscore': 			'../../vendor/underscore/underscore',
		'backbone': 			'../../vendor/backbone/backbone',
		'backbone.wreqr': 		'../../vendor/backbone.wreqr/lib/backbone.wreqr.min',
		'backboneRelational':	'../../vendor/backbone-relational/backbone-relational',
		'marionette': 			'../../vendor/marionette/lib/backbone.marionette',
		'foundation' : 			basePath + 'foundation-sites/dist/plugins/foundation.core',
		'foundationMediaQuery':	basePath + 'foundation-sites/dist/plugins/foundation.util.mediaQuery',
		'foundationEqualizer' : basePath + 'foundation-sites/dist/plugins/foundation.equalizer',
		'foundationOffCanvas': 	basePath + 'foundation-sites/dist/plugins/foundation.offcanvas',
		'foundationTooltip':	basePath + 'foundation-sites/dist/plugins/foundation.tooltip',
		'foundationTriggers':	basePath + 'foundation-sites/dist/plugins/foundation.util.triggers',
		'foundationMotion':		basePath + 'foundation-sites/dist/plugins/foundation.util.motion',
		'foundationTimerAndImageLoader': basePath + 'foundation-sites/dist/plugins/foundation.util.timerAndImageLoader',
		'modernizr' : 			'../../vendor/modernizr/modernizr',
		'text': 				'../../vendor/requirejs-text/text',
		'tpl': 					'../../vendor/requirejs-tpl/tpl',
		'async': 				'../../vendor/requirejs-plugins/src/async',
		'stripe':				'https://js.stripe.com/v2/?noext',
		'backbone.poller':		'../../vendor/backbone-poller/backbone.poller',
		'moment':				'../../vendor/moment/moment',
		'behaviors':			'../../vendor/UsefulMarionetteViewBehaviors',
		'shared':				'../../shared',
		'nprogress':			'../../vendor/nprogress/nprogress'
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
	'shared/js/Brain',
	'shared/admin-retailer/js/views/RootView',
	'controllers/Controller',
	'util/Router',
	'shared/js/models/Vendor',
	'shared/js/models/Session',
	'shared/js/models/Config',
	'foundation',
	'foundationMediaQuery'
], function (
	app,
	Backbone,
	Brain,
	RootView,
	Controller,
	Router,
	Vendor,
	Session,
	Config
) {
	$(document).foundation();

	app.on('start', function() {
		Brain.store('session', new Session());

		app.rootView = new RootView();
		var controller = new Controller({
			rootView: app.rootView
		});

		app.vendor = new Vendor();
		Brain.store('user', app.vendor);
		app.rootView.render();
		app.router = new Router({ controller: controller });
		
		// subscribe to error codes
		$.ajaxSetup({
			statusCode: {
				401 : function() {
					app.router.navigate('retailer/login', { trigger: true });
				},
				403 : function() {
					app.router.navigate('retailer/login', { trigger: true });
				}
			}
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
