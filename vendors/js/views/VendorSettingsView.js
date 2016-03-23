/**
 * Created by Feek on 3/23/16.
 */
define([
	'marionette',
	'../../../shared/js/behaviors/Modal',
	'tpl!templates/vendor-settings.html'
], function (
	Mn,
	Modal,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,
		tagName: 'div',
		className: 'modal',

		templateHelpers: function() {
			var view = this;

			return {

			}
		},

		behaviors: {
			Modal: {
				behaviorClass: Modal
			}
		},

		events: {},

		ui: {},

		initialize: function (options) {
		}
	});

	return view;
});
