/**
 * Created by Feek on 3/16/16.
 */
define([
	'marionette',
	'shared/js/util/Vent',
	'App',
	'tpl!templates/header.html'
], function (
	Mn,
	Vent,
	app,
	tpl
) {
	var HeaderView = Mn.ItemView.extend({
		template: tpl,
		className: 'top-bar',

		templateHelpers: function() {
			return {
				username: this.model.get('name')
			}
		},

		events: {
			'click @ui.settings' : 'showSettings',
			'click @ui.hamburger' : 'toggleOffCanvas'
		},

		modelEvents: {
			'change:site_status' : 'updateStatusDisplay',
			'sync' : 'updateStatusDisplay'
		},

		ui: {
			settings	: '.settings',
			hamburger 	: '.menu-icon',
			status		: '.status'
		},

		onRender: function() {
			if (Foundation.MediaQuery.atLeast('large')) {
				this.ui.hamburger.hide();
			}

			this.listenTo($(window).on('changed.zf.mediaquery', function(event, newSize, oldSize) {
				// newSize is the name of the now-current breakpoint, oldSize is the previous breakpoint
				if (newSize === 'large') {
					this.ui.hamburger.hide();
				} else {
					this.ui.hamburger.show();
				}
			}.bind(this)));

			this.updateStatusDisplay();
		},

		toggleOffCanvas: function(evt) {
			evt.preventDefault();
			app.rootView.trigger('toggleOffCanvas', evt);
		},

		initialize: function (options) {
			this.model = options.model; // vendor
		},

		showSettings: function() {
			Vent.trigger('settings:show');
		},

		updateStatusDisplay: function() {
			var status = this.model.get('store_status');
			this.ui.status.html(status);
		}
	});

	return HeaderView;
});
