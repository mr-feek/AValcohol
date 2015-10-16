define([
	'marionette',
	'tpl!templates/product.html'
], function (Mn,
			 tpl) {
	var ProductView = Mn.ItemView.extend({
		template: tpl,
		tagName: 'li',
		className: '',

		events: {},

		ui: {},

		initialize: function (options) {
			console.log(this.model);
		},
	});

	return ProductView;
});
