define([
	'backbone',
	'../../../shared/js/models/User',
	'../../../shared/js/models/Product',
	'../../../shared/js/models/UserAddress',
	'backboneRelational'
], function (
	Backbone,
	User,
	UserAddress,
	Product
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
				key: 'address',
				relatedModel: UserAddress
			}
		],

		defaults: {
			products: null,
			user: null,
			address: null,
			stripe_token: null,
			note: null
		},

		parse: function(response, xhr) {
			return response.order;
		},
	});

	return Order;
});
