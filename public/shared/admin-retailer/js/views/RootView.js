/**
 * Created by Feek on 3/16/16.
 */
define([
	'marionette',
	'shared/js/views/FooterView',
	'shared/js/util/Vent',
	'tpl!../templates/root.html'
], function (
	Mn,
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
			footer		: 'footer'
		},

		initialize: function (options) {
			Vent.on('modal:close', this.closeModal, this);
			//Vent.on('settings:show', this.showSettings, this);
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
