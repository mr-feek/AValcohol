/**
 * Created by Feek on 3/22/16.
 */

define([
	'marionette',
	'templates/sidebar.html'
], function (
	Mn,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,
		tagName: 'div',
		className: 'sidebar',

		templateHelpers: function() {
			var view = this;

			return {

			}
		},

		events: {},

		ui: {},

		initialize: function (options) {
		}
	});

	return view;
});
