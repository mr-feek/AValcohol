define([
	'backbone',
	'../../../shared/js/models/User',
	'../../../shared/js/models/Product',
	'../../../shared/js/models/OrderStatus',
	'../../../shared/js/models/UserAddress',
	'../../../shared/js/models/OrderDeliveryDetails',
	'backboneRelational'
], function (
	Backbone,
	User,
	Product,
	OrderStatus,
	UserAddress,
	DeliveryDetails
) {
	var Order = Backbone.RelationalModel.extend({
		urlRoot: '/api/order',
		idAttribute: 'id',

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
			{
				type: Backbone.HasOne,
				key: 'address',
				relatedModel: UserAddress,
				includeInJSON: true
			},
			{
				type: Backbone.HasOne,
				key: 'delivery_details',
				relatedModel: DeliveryDetails,
				includeInJSON: false,
				reverseRelation: {
					key: 'order',
					type: Backbone.HasOne,
					includeInJSON: 'id'
				}
			}
		],

		defaults: {
			products: null,
			user: null,
			stripe_token: null,
			note: null,
			delivery_details: null
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
