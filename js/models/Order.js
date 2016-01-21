define([
	'backbone',
	'models/User',
	'models/Product',
	'models/UserAddress',
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
		}
	});

	return Order;
});
