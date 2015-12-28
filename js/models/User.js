define([
	'backbone',
	'backboneRelational'
], function (
	Backbone
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

		defaults: {
			mvp_user: 1 // this account does NOT need a password, email, etc
		}
	});

	return User;
});
