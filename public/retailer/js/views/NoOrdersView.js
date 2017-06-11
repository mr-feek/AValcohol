/**
 * Created by Feek on 5/17/16.
 */
define([
	'marionette',
	'tpl!templates/no-orders.html'
], function(
	Mn,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,

		events: {
		},

		ui: {
		},

		initialize: function(options) {
		}
	});

	return view;
});
