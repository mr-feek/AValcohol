/**
 * Created by Feek on 3/16/16.
 */
define([
	'marionette',
	'views/HeaderView',
	'views/LoginView',
	'views/VendorHomeRootView',
	'views/VendorSettingsView',
	'../../../shared/js/util/Vent',
	'App',
	'tpl!templates/root.html'
], function (
	Mn,
	HeaderView,
	LoginView,
	VendorHomeRootView,
	VendorSettingsView,
	Vent,
	app,
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

		onRender: function () {
			/*
			this.getRegion('header').show(new HeaderView( {
				model: app.vendor
			}));
			*/
			this.getRegion('main').show(new LoginView({	model: app.vendor }));
			//this.getRegion('main').show(new VendorHomeRootView());
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
