/**
 * Created by Feek on 3/16/16.
 */
define([
	'marionette',
	'shared/js/util/Vent',
	'models/SiteStatusModel',
	'App',
	'templates/header.html'
], function (
	Mn,
	Vent,
	SiteStatusModel,
	app,
	tpl
) {
	var HeaderView = Mn.ItemView.extend({
		template: tpl,
		className: 'top-bar',

		templateHelpers: function() {
			return {
				status: this.model.get('status')
			}
		},

		events: {
			'click @ui.hamburger' : 'toggleOffCanvas',
			'click @ui.siteStatus' : 'siteStatusClicked'
		},

		ui: {
			hamburger : '.menu-icon',
			siteStatus : '.site-status'
		},

		initialize: function(options) {
			this.model = new SiteStatusModel();
			this.model.fetch().done(function() {
				this.render();
			}.bind(this));
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

			this.updateSiteStatusDisplay();
		},

		/**
		 * an admin can only update if we are within "open hours"
		 */
		updateSiteStatusDisplay: function () {
			if (this.model.isOnline()) {
				this.ui.siteStatus.prop('checked', true);
			} else {
				this.ui.siteStatus.prop('checked', false);
			}

			if (!this.model.adminCanUpdate()) {
				this.ui.siteStatus.prop('disabled', true);
			}
		},

		siteStatusClicked: function(evt) {
			var confirmed = confirm('Only use this toggle in case of emergency. If the store is currently open (within delivery hours) you can force it to be turned off now. If you proceed, you MUST remember to turn the store back on, or else it will remain off indefinitely (including tomorrow)');
			if (!confirmed) { return; }

			if (this.ui.siteStatus.prop('checked')) {
				this.model.setOnline();
			}
			else {
				this.model.setOffline();
			}

			this.model.save().done(function(response) {
				this.render();
			}.bind(this));
		},

		toggleOffCanvas: function(evt) {
			evt.preventDefault();
			app.rootView.trigger('toggleOffCanvas', evt);
		}
	});

	return HeaderView;
});
