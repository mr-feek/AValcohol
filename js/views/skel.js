define([
	'marionette',
	'tpl!templates/.html'
], function (
	Mn,
	tpl
) {
	var = Mn.ItemView.extend({
		template: tpl,
		tagName: '',
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

	return ;
});
