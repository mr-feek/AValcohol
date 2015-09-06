define([
	'marionette',
	'views/HeaderView',
	'views/HomeView',
	'tpl!templates/root.html'
], function(
	Mn,
	HeaderView,
	HomeView,
	tpl
) {
	var RootView = Mn.LayoutView.extend({
		template: tpl,
		el: 'body',

		events: {
		},

		ui: {
		},

		regions: {
			header: 'header',
			main: '#main'
		},

		initialize: function(options) {
		},

		onRender: function() {
			this.getRegion('header').show(new HeaderView());
			this.getRegion('main').show(new HomeView());
		}
	});

	return RootView;
});
