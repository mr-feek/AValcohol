/**
 * Created by Feek on 3/23/16.
 */
define([
	'marionette',
	'behaviors/Modal',
	'behaviors/ModelFormSave',
	'templates/vendor-settings.html',
	'foundationTooltip'
], function (
	Mn,
	Modal,
	ModelFormSave,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,
		tagName: 'div',

		templateHelpers: function() {
			var view = this;

			return {

			}
		},

		behaviors: {
			Modal: {
				behaviorClass: Modal
			},
			ModelFormSave: {
				behaviorClass: ModelFormSave
			}
		},

		events: {},

		ui: {},

		initialize: function (options) {
		},

		onShow: function() {
			$(document).foundation();
			Foundation.reInit('tooltip');
		}
	});

	return view;
});
