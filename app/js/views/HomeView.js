define([
	'marionette',
	'tpl!templates/home.html'
], function(
	Mn,
	tpl
) {
	var HomeView = Mn.ItemView.extend({
		template: tpl,

		events: {
		},

		ui: {
			'carousel' : '.carousel'
		},

		initialize: function(options) {
		},

		onShow: function() {
			this.ui.carousel.slick({
				autoplay: true,
				arrows: false,
				pauseOnHover: false
			});
		}
	});

	return HomeView;
});
