define([
	'backbone',
	'backboneRelational',
	'models/UserAddress'
], function (
	Backbone,
	BackboneRelational,
	UserAddress
) {
	var User = Backbone.RelationalModel.extend({
		urlRoot: '/api/user/',

		/*
		relations: [
			{
				type: Backbone.HasMany,
				key: 'previous_orders',
				relatedModel: 'Order'
			}
		],
		*/

		relations: [
			{
				type: Backbone.HasOne,
				key: 'address',
				relatedModel: UserAddress,
				includeInJSON: false
			}
		],

		defaults: {
			mvp_user: 1 // this account does NOT need a password, email, etc
		}
	});

	return User;
});
