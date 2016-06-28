/**
 * Created by Feek on 3/16/16.
 */
define([
	'marionette',
	'shared/js/util/Vent',
	'models/SiteStatusModel',
	'App',
	'tpl!templates/header.html'
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
			'click @ui.hamburger' : 'toggleOffCanvas'
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

			this.updateSiteStatusColor();
		},

		updateSiteStatusColor: function () {
			if (this.model.isOnline()) {
				this.ui.siteStatus.removeClass('off');
				this.ui.siteStatus.addClass('on');
			} else {
				this.ui.siteStatus.addClass('off');
				this.ui.siteStatus.removeClass('on');
			}
		},

		toggleOffCanvas: function(evt) {
			evt.preventDefault();
			app.rootView.trigger('toggleOffCanvas', evt);
		}
	});

	return HeaderView;
});
