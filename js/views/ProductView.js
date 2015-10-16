define([
	'marionette',
	'tpl!templates/product.html'
], function (Mn,
			 tpl) {
	var ProductView = Mn.ItemView.extend({
		template: tpl,

		events: {},

		ui: {},

		initialize: function (options) {
			console.log('product view');
		},
	});

	return ProductView;
});
