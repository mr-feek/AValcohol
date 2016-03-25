/**
 * Created by Feek on 3/23/16.
 */
define([
	'marionette',
	'../../../shared/js/behaviors/Modal',
	'../../../shared/js/behaviors/ModelFormSave',
	'tpl!templates/vendor-settings.html',
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
		className: 'modal',

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
			$(document).foundation('tooltip', 'reflow');
		}
	});

	return view;
});
