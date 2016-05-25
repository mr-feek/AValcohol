define([
	'backbone',
	'shared/js/models/Product'
], function (
	Backbone,
	Product
) {
	var Products = Backbone.Collection.extend({
		url: '/api/product',
		model: Product,

		parse: function(response, options) {
			return response.products
		}
	});

	return Products;
});
