/**
 * Created by Feek on 10/13/15.
 */

define([
	'marionette',
	'views/UserHomeView'
], function (
	Marionette,
	UserHomeView
) {
	var Controller = Marionette.Object.extend({
		rootView: null,

		initialize: function(options) {
			this.rootView = options.rootView;
		},

		showUserHome: function() {
			console.log('show user home');
			this.rootView.getRegion('main').show(new UserHomeView());
		}
	});

	return Controller;
});
