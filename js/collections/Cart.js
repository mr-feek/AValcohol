define([
	'backbone',
	'../models/Product',
	'util/Vent',
], function (
	Backbone,
	Product,
	Vent
) {
	var Cart = Backbone.Collection.extend({
		urlRoot: 'php/api/',
		model: Product,

		initialize: function() {

		}
	});

	return Cart;
});
