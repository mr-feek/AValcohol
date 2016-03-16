/**
 * Created by Feek on 3/16/16.
 */
define([
	'marionette',
	'views/HeaderView',
	'util/Vent',
	'tpl!templates/root.html'
], function (
	Mn,
	HeaderView,
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
		},

		onRender: function () {
			this.getRegion('header').show(new HeaderView());
			// main region is populated by the router
		},

		closeModal: function() {
			this.getRegion('modalRegion').empty();
		},
	});

	return RootView;
});
