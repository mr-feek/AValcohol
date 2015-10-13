/**
 * Created by Feek on 10/13/15.
 */

define([
	'marionette',
], function (Marionette) {
	var Controller = Marionette.Object.extend({
		showUserHome: function() {
			console.log('show user home');
			$('body').html('user home ayyy');
		}
	});

	return Controller;
});
