define([
	'marionette',
	'tpl!templates/billing-info-entry.html'
], function (
	Mn,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,
		tagName: 'form',
		className: '',

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
