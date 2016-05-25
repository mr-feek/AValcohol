define([
	'marionette',
	'views/landing/HeaderView',
	'views/landing/MVPHomeView',
	'shared/js/util/Vent',
	'tpl!templates/root.html'
], function (
	Mn,
	HeaderView,
	MVPHomeView,
	Vent,
	tpl
) {
	var RootView = Mn.LayoutView.extend({
		template: tpl,
		el: '#mount-point',
		$offCanvasWrap: null,

		events: {},

		childEvents: {
			// every time a view is shown inside a region we need to make sure foundation listeners are applied
			show: function() {
				$(document).foundation();
				$(document).foundation('offcanvas', 'reflow');
				$(document).foundation('alert', 'reflow');
			}
		},

		regions: {
			header: 'header',
			main: '#main',
			rightOffCanvas: '.right-off-canvas-menu',
			modalRegion: '.modal-region'
		},

		initialize: function (options) {
			Vent.on('root:scrollTo', this.scrollTo);
			Vent.on('modal:close', this.closeModal, this);
		},

		onRender: function () {
			this.getRegion('header').show(new HeaderView());
			// main region is populated by the router
		},

		/**
		 * closes the off canvas
		 * @param bool cleanup, whether or not to empty the off canvas region
		 */
		closeOffCanvas: function(cleanup) {
			var view = this;

			if (!this.$offCanvasWrap) {
				this.$offCanvasWrap = $('.off-canvas-wrap')
			}

			this.$offCanvasWrap.foundation('offcanvas', 'hide', 'move-left');

			if (cleanup) {
				// race condition for one second.
				setTimeout(function() {
					view.getRegion('rightOffCanvas').empty();
				}, 1000);
			}
		},

		closeModal: function() {
			this.getRegion('modalRegion').empty();
		},

		openOffCanvas: function() {
			if (!this.$offCanvasWrap) {
				this.$offCanvasWrap = $('.off-canvas-wrap')
			}
			this.$offCanvasWrap.foundation('offcanvas', 'show', 'move-left');
		}
	});

	return RootView;
});
