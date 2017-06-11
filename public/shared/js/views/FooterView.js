/**
 * Created by Feek on 6/22/16.
 */
define([
	'marionette',
	'tpl!../../js/templates/footer.html'
], function (
	Mn,
	tpl
) {
	var FooterView = Mn.ItemView.extend({
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

	return FooterView;
});
