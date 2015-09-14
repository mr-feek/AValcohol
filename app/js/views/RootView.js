define([
	'marionette',
	'views/HeaderView',
	'views/HomeView',
	'util/Vent',
	'tpl!templates/root.html'
], function(
	Mn,
	HeaderView,
	HomeView,
	Vent,
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
			var view = this;
			Vent.on('root:scrollTo', view.scrollTo);
		},

		onRender: function() {
			this.getRegion('header').show(new HeaderView());
			this.getRegion('main').show(new HomeView());
		},

		scrollTo: function(selector) {
			$('html, body').animate({
				scrollTop: $(selector).offset().top}, 500);
		}
	});

	return RootView;
});
