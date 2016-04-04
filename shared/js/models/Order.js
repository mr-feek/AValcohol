define([
	'backbone',
	'../../../shared/js/models/User',
	'../../../shared/js/models/Product',
	'../../../shared/js/models/OrderStatus',
	'backboneRelational'
], function (
	Backbone,
	User,
	Product,
	OrderStatus
) {
	var Order = Backbone.RelationalModel.extend({
		urlRoot: '/api/order',

		relations: [
			{
				type: Backbone.HasOne,
				key: 'user',
				relatedModel: User
			},
			{
				type: Backbone.HasMany,
				key: 'products',
				relatedModel: Product
			},
			{
				type: Backbone.HasOne,
				key: 'status',
				relatedModel: OrderStatus,
				includeInJSON: false,
				reverseRelation: {
					key: 'order',
					type: Backbone.HasOne,
					includeInJSON: false
				}
			},
		],

		defaults: {
			products: null,
			user: null,
			stripe_token: null,
			note: null
		},

		parse: function(response, xhr) {
			// if this is a request made for a singular model, the model will be stored in this location
			if (response.order) {
				return response.order;
			}

			// otherwise it's part of a collection, and just return response so it doesn't break backbone relational
			return response;
		},
	});

	return Order;
});
