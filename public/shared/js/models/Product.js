define([
	'backbone',
	'backboneRelational'
], function (
	Backbone
) {
	var Product = Backbone.RelationalModel.extend({
		urlRoot: '/api/vendor/',

		url: function() {
			return this.urlRoot + this.get('vendor_id') + '/product/' + this.id;
		},

		defaults: {
			quantity: 1, // for use in cart,
			inCart: false // if this product is in the cart or not
		},

		parse: function(response) {
			if (response.product) {
				return response.product;
			}
			return response;
		}
	});

	return Product;
});
