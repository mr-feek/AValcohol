define([
	'backbone',
	'models/User',
	'models/Product',
	'backboneRelational'
], function (
	Backbone,
	User,
	Product
) {
	var Order = Backbone.RelationalModel.extend({
		urlRoot: '/api/order/',

		relations: [
			{
				type: Backbone.HasOne,
				key: 'user',
				relatedModel: 'User'
			},
			{
				type: Backbone.HasMany,
				key: 'products',
				relatedModel: 'Product'
			}
		],

		defaults: {
			product: undefined,
			user: undefined
		}
	});

	return Order;
});
