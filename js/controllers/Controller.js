/**
 * Created by Feek on 10/13/15.
 */

define([
	'marionette',
	'views/HeaderView',
	'views/MVPHomeView',
	'views/UserHomeView'
], function (
	Marionette,
	HeaderView,
	MVPHomeView,
	UserHomeView
) {
	var Controller = Marionette.Object.extend({
		rootView: null,

		initialize: function(options) {
			this.rootView = options.rootView;
		},

		showHome: function() {
			this.rootView.getRegion('main').show(new MVPHomeView());
		},

		showUserHome: function() {
			this.rootView.getRegion('main').show(new UserHomeView());
		}
	});

	return Controller;
});
