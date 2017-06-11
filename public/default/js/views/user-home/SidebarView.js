/**
 * Created by Feek on 6/21/16.
 */
define([
	'marionette',
	'tpl!templates/user-home/sidebar.html'
], function (
	Mn,
	tpl
) {
	var SidebarView = Mn.ItemView.extend({
		template: tpl,
		className: 'text-center',

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

	return SidebarView;
});
