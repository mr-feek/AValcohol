define([
	'backbone',
	'models/Product'
], function (
	Backbone,
	Product
) {
	var FeaturedProducts = Backbone.Collection.extend({
		url: 'php/api/product/featured',
		model: Product
	});

	return FeaturedProducts;
});
