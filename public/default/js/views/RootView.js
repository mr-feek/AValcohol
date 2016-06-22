define([
	'marionette',
	'views/HeaderView',
	'views/landing/LandingView',
	'shared/js/util/Vent',
	'tpl!templates/root.html'
], function (
	Mn,
	HeaderView,
	LandingView,
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
			//todo: do we need this to happen or should we not be lazy and only call these when necessary? depends how expensive this ends up being
			show: function() {
				//$(document).foundation(); // move to mainjs app start? probably
				//Foundation.reInit('offcanvas');
				//Foundation.reInit('alert');
			}
		},

		regions: {
			header: 'header',
			main: '#main',
			offCanvas: '.off-canvas',
			modalRegion: '.modal-mount-point'
		},

		ui: {
			'modalWrapper' : '.modal-region'
		},

		initialize: function (options) {
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
				this.$offCanvasWrap = $('.off-canvas-wrapper');
			}

			this.$offCanvasWrap.foundation('close', function() {
				if (cleanup) {
					view.getRegion('offCanvas').empty();
				}
			});
		},

		closeModal: function() {
			this.getRegion('modalRegion').empty();
		},

		openOffCanvas: function(evt) {
			if (!this.$offCanvasWrap) {
				this.initializeOffCanvas();
			}

			this.$offCanvasWrap.foundation('open', evt, evt.trigger);
		},

		initializeOffCanvas: function() {
			this.$offCanvasWrap = $('.off-canvas-wrapper');
			
			var elem = new Foundation.OffCanvas(this.$offCanvasWrap, {
				'forceTop' : false
			});

			this.$offCanvasWrap.on('opened.zf.offcanvas', function() {
				$('#all-wrapper').css({
					'overflow' : 'hidden',
					'height' : '100vh'
				});
			});

			this.$offCanvasWrap.on('closed.zf.offcanvas', function() {
				$('#all-wrapper').css({
					'overflow' : 'initial',
					'height' : 'initial'
				});
			})
		}
	});

	return RootView;
});
