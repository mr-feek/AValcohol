/**
 * Created by Feek on 7/2/16.
 */
define([
	'marionette',
	'templates/misc/terms-and-conditions.html'
], function (
	Mn,
	tpl
) {
	var TermsAndConditionsView = Mn.ItemView.extend({
		template: tpl,
		className: 'columns small-12',

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

	return TermsAndConditionsView;
});
