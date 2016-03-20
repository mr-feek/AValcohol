/**
 * Created by Feek on 3/16/16.
 */
define([
	'marionette',
	'views/HeaderView',
	'views/LoginView',
	'util/Vent',
	'App',
	'tpl!templates/root.html'
], function (
	Mn,
	HeaderView,
	LoginView,
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
		},

		onRender: function () {
			this.getRegion('header').show(new HeaderView());
			this.getRegion('main').show(new LoginView({	model: app.vendor }));
		},

		closeModal: function() {
			this.getRegion('modalRegion').empty();
		},
	});

	return RootView;
});
