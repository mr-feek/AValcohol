/**
 * Created by Feek on 5/27/16.
 */
define([
	'marionette',
	'behaviors/Modal',
	'tpl!templates/user-home/store-closed.html'
], function(
	Mn,
	Modal,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,

		behaviors: {
			Modal: {
				behaviorClass: Modal
			},
		},

		events: {
		},

		ui: {
		},

		initialize: function(options) {
		}
	});

	return view;
});
