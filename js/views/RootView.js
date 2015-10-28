define([
	'marionette',
	'views/HeaderView',
	'views/MVPHomeView',
	'util/Vent',
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
		el: 'body',

		events: {},

		ui: {},

		childEvents: {
			// every time a view is shown inside a region we need to make sure foundation listeners are applied
			// currently only reflowing for offcanvas (cart)
			show: function() {
				$(document).foundation();
				$(document).foundation('offcanvas', 'reflow');
			}
		},

		regions: {
			header: 'header',
			main: '#main',
			rightOffCanvas: '.right-off-canvas-menu'
		},

		initialize: function (options) {
			var view = this;
			Vent.on('root:scrollTo', view.scrollTo);
		},

		onRender: function () {
			this.getRegion('header').show(new HeaderView());
			// main region is populated by the router
		},

		scrollTo: function (selector) {
			$('html, body').animate({
				scrollTop: $(selector).offset().top
			}, 500);
		}
	});

	return RootView;
});
