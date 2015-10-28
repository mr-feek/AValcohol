define([
	'backbone',
	'util/Vent'
], function (
	Backbone,
	Vent
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
			Vent.trigger('home:updateNumProducts');
		}
	});

	return Cart;
});
