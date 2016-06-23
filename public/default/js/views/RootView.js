define([
	'marionette',
	'views/HeaderView',
	'shared/js/views/FooterView',
	'views/landing/LandingView',
	'shared/js/util/Vent',
	'tpl!templates/root.html',
	'behaviors/FoundationOffCanvas'
], function (
	Mn,
	HeaderView,
	FooterView,
	LandingView,
	Vent,
	tpl,
	FoundationOffCanvas
) {
	var RootView = Mn.LayoutView.extend({
		template: tpl,
		el: '#mount-point',

		behaviors: {
			FoundationOffCanvas: {
				behaviorClass: FoundationOffCanvas
			}
		},

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
			header		: 'header',
			main		: '#main',
			offCanvas	: '.off-canvas',
			modalRegion	: '.modal-mount-point',
			footer		: 'footer'
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
			this.getRegion('footer').show(new FooterView());
		},

		closeModal: function() {
			this.getRegion('modalRegion').empty();
		}
	});

	return RootView;
});
