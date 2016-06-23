/**
 * Created by Feek on 3/16/16.
 */
define([
	'marionette',
	'views/SidebarView',
	'shared/js/views/FooterView',
	'shared/js/util/Vent',
	'tpl!../templates/root.html',
], function (
	Mn,
	SidebarView,
	FooterView,
	Vent,
	tpl
) {
	var RootView = Mn.LayoutView.extend({
		template: tpl,
		el: '#mount-point',

		events: {},

		regions: {
			header		: 'header',
			main		: '#main',
			modalRegion	: '.modal-mount-point',
			offCanvas	: '.off-canvas',
			sidebar		: '.sidebar-region',
			footer		: 'footer'
		},

		/**
		 * optional sidebarView class
		 * @param options
		 */
		initialize: function (options) {
			Vent.on('modal:close', this.closeModal, this);
		},

		onRender: function() {
			this.getRegion('footer').show(new FooterView());
			this.getRegion('sidebar').show(new SidebarView());
			this.getRegion('offCanvas').show(new SidebarView());
		},

		closeModal: function() {
			this.getRegion('modalRegion').empty();
		}
	});

	return RootView;
});
