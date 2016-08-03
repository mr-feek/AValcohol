/**
 * Created by Feek on 5/27/16.
 */
define([
	'marionette',
	'App',
	'behaviors/Modal',
	'tpl!templates/user-home/store-closed.html'
], function(
	Mn,
	app,
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

		templateHelpers: function() {
			return {
				message: app.config.get('closedMessage')
			}
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
