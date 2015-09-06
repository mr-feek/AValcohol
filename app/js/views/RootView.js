define([
	'marionette',
	'views/HeaderView',
	'tpl!templates/root.html'
], function(
	Mn,
	HeaderView,
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
		}
	});

	return RootView;
});
