/**
 * Created by Feek on 3/16/16.
 */
define([
	'marionette',
	'views/SidebarView',
	'shared/js/views/FooterView',
	'shared/js/util/Vent',
	'tpl!../templates/root.html',
	'behaviors/FoundationOffCanvas'
], function (
	Mn,
	SidebarView,
	FooterView,
	Vent,
	tpl,
	FoundationOffCanvas
) {
	var RootView = Mn.LayoutView.extend({
		template: tpl,
		el: '#mount-point',

		events: { },

		ui: { },

		regions: {
			header		: 'header',
			main		: '#main',
			modalRegion	: '.modal-mount-point',
			offCanvas	: '.off-canvas',
			sidebar		: '.sidebar-region',
			footer		: 'footer'
		},

		behaviors: {
			FoundationOffCanvas: {
				behaviorClass: FoundationOffCanvas,
				forceTop: false,
				disableScrollWhileOpen: false
			}
		},

		/**
		 * this class has a sidebar loaded in sidebar region and off canvas. in large viewports, sidebar is shown.
		 * on smaller, off canvas is used
		 * @param options
		 */
		initialize: function (options) {
			Vent.on('modal:close', this.closeModal, this);
		},

		onRender: function() {
			// rest populated by controller
			this.getRegion('footer').show(new FooterView());
		},

		closeModal: function() {
			this.getRegion('modalRegion').empty();
		}
	});

	return RootView;
});
