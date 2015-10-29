define([
	'marionette',
	'tpl!templates/checkout.html'
], function (
	Mn,
	tpl
) {
	var CheckoutView = Mn.ItemView.extend({
		template: tpl,
		tagName: 'div',
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

	return CheckoutView;
});
