/**
 * Created by Feek on 3/16/16.
 */
define([
	'marionette',
], function (
	Marionette
) {
	var Controller = Marionette.Object.extend({
		initialize: function(options) {
			this.rootView = options.rootView;
		}
	});

	return Controller;
});
