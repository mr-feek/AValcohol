/**
 * Created by Feek on 10/13/15.
 */

define([
	'marionette'
], function (
	Marionette
) {
	var Controller = Marionette.Object.extend({
		rootView: null,

		initialize: function(options) {
			this.rootView = options.rootView;
		},

		showUserHome: function() {
			//rootView.getRegion('main').show(new HeaderView());
			console.log('show user home');
		}
	});

	return Controller;
});
