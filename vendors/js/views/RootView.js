/**
 * Created by Feek on 3/16/16.
 */
define([
	'marionette',
	'views/VendorSettingsView',
	'../../../shared/js/util/Vent',
	'tpl!templates/root.html'
], function (
	Mn,
	VendorSettingsView,
	Vent,
	tpl
) {
	var RootView = Mn.LayoutView.extend({
		template: tpl,
		el: '#mount-point',

		events: {},

		regions: {
			header: 'header',
			main: '#main',
			modalRegion: '.modal-region'
		},

		initialize: function (options) {
			Vent.on('modal:close', this.closeModal, this);
			Vent.on('settings:show', this.showSettings, this);
		},

		closeModal: function() {
			this.getRegion('modalRegion').empty();
		},

		showSettings: function() {
			this.getRegion('modalRegion').show(new VendorSettingsView());
		}
	});

	return RootView;
});
