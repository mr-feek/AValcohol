define([
	'marionette',
	'views/RootView'
], function (Mn,
			 RootView) {
	var App = Mn.Application.extend({
		initialize: function (options) {
			window.app = this; // storing globally yolo
			this.rootView = new RootView();
		},

		onStart: function () {
			this.rootView.render();
		},
	});

	return App;
});
