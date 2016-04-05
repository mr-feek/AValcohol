define([
	'marionette'
], function (Mn) {
	var App = Mn.Application.extend({
		initialize: function (options) {
		},

		onStart: function () {
			//this.rootView.render();
		}
	});

	return new App();
});