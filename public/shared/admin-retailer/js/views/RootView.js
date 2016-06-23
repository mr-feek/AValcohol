/**
 * Created by Feek on 3/16/16.
 */
define([
	'marionette',
	'shared/js/views/FooterView',
	'shared/js/util/Vent',
	'tpl!../templates/root.html',
	'behaviors/FoundationOffCanvas',
], function (
	Mn,
	FooterView,
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

		regions: {
			header		: 'header',
			main		: '#main',
			modalRegion	: '.modal-mount-point',
			offCanvas	: '.off-canvas',
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
		},

		closeModal: function() {
			this.getRegion('modalRegion').empty();
		}
	});

	return RootView;
});
