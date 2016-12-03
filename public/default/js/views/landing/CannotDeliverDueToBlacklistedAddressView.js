/**
 * Created by Feek on 8/9/16.
 */
define([
	'marionette',
	'behaviors/Modal',
	'tpl!templates/landing/cannot-deliver-due-to-blacklisted-address.html'
], function (
	Mn,
	Modal,
	tpl
) {
	var CannotDeliverDueToBlacklistedAddressView = Mn.ItemView.extend({
		template: tpl,
		message: null,

		templateHelpers: function () {
			return {
				message: this.message
			}
		},

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
			this.message = options.message;
		}
	});

	return CannotDeliverDueToBlacklistedAddressView;
});
