define([
	'backbone',
], function (
	Backbone
) {
	var Product = Backbone.Model.extend({
		urlRoot: '/api/',

		defaults: {
			quantity: 1 // for use in cart
		}
	});

	return Product;
});
