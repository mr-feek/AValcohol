/**
 * Created by Feek on 3/16/16.
 */
define([
	'marionette',
	'shared/js/util/Vent',
	'models/SiteStatusModel',
	'App',
	'shared/js/Brain',
	'tpl!templates/header.html'
], function (
	Mn,
	Vent,
	SiteStatusModel,
	app,
	Brain,
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
			'click @ui.siteStatus' : 'siteStatusClicked',
			'click @ui.logout' : 'onLogoutClicked'
		},

		ui: {
			hamburger : '.menu-icon',
			siteStatus : '.site-status',
			logout: '.js-logout'
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

		updateSiteStatusDisplay: function () {
			if (this.model.isForcedClosed()) {
				this.ui.siteStatus.prop('checked', true);
			} else {
				this.ui.siteStatus.prop('checked', false);
			}
		},

		siteStatusClicked: function(evt) {
			var confirmed = confirm('Only use this toggle in case of emergency. If the store is currently open (within delivery hours) you can force it to be turned off now. If you proceed, you MUST remember to turn the store back on, or else it will remain off indefinitely (including tomorrow)');
			if (!confirmed) { return; }

			if (this.ui.siteStatus.prop('checked')) {
				this.model.forceStoreClosed();
			}
			else {
				this.model.removeForceStoreClosed();
			}

			this.model.save().done(function(response) {
				this.render();
			}.bind(this));
		},

		toggleOffCanvas: function(evt) {
			evt.preventDefault();
			app.rootView.trigger('toggleOffCanvas', evt);
		},

		onLogoutClicked: function() {
			var session = Brain.retrieve('session');
			session.logout();
		}
	});

	return HeaderView;
});
