/**
 * Created by Feek on 3/22/16.
 */
define([
	'marionette',
	'tpl!templates/order.html'
], function (
	Mn,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,
		tagName: 'div',
		className: '',

		templateHelpers: function() {
			var view = this;

			return {
				user_name: this.model.get('user').first + ' ' + this.model.get('user').last,
				dob: this.model.get('user').dob,
				orderNumber: this.model.get('id'),
				timePlaced: this.model.get('created_at')
			}
		},

		events: {},

		ui: {},

		initialize: function (options) {
		}
	});

	return view;
});
