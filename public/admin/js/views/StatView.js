/**
 * Created by Feek on 6/13/16.
 */
define([
	'marionette',
	'tpl!templates/dashboard.html'
], function (
	Mn,
	tpl
) {
	var StatView = Mn.ItemView.extend({
		template: tpl,

		templateHelpers: function () {
			return {}
		},
		 /*
		behaviors: {
			Modal: {
				behaviorClass: Modal
			},
		},
		*/

		events: {
		},

		ui: {
		},

		initialize: function( options) {
		}
	});

	return StatView;
});
