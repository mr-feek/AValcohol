define([
	'backbone'
], function (
	Backbone
) {
	var Cart = Backbone.Model.extend({
		urlRoot: 'php/api/',
		initialize: function() {

		},

		defaults: {
			products: [],
			user: null,
		},

		addProductToCart: function(product) {
			this.get('products').push(product);
			this.trigger('change');
			this.trigger('change:products');
			console.log('change products triggered');
		}
	});

	return Cart;
});
