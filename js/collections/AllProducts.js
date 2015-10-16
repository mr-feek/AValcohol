define([
	'backbone',
	'models/Product'
], function (
	Backbone,
	Product
) {
	var AllProducts = Backbone.Collection.extend({
		url: 'php/api/product/all',
		model: Product
	});

	return AllProducts;
});
