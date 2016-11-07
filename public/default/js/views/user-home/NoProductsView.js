define([
	'marionette',
	'templates/user-home/no-products.html'
], function (
	Mn,
	tpl
) {
	var NoProductsView = Mn.ItemView.extend({
		template: tpl,
		className: 'text-center no-products',

		events: {},

		ui: {},

		initialize: function (options) {
		},

		onShow: function() {
			this.$el.animateCss('jello');
		}
	});

	return NoProductsView;
});
