define([
	'backbone',
	'backboneRelational',
	'models/User'
], function (
	Backbone,
	BackboneRelational,
	User
) {
	var UserAddress = Backbone.RelationalModel.extend({
		urlRoot: '/api/user/address',

		relations: [
			{
				type: Backbone.HasOne,
				key: 'user',
				relatedModel: User,
				includeInJSON: false
			}
		],

		defaults: {
			city: undefined,
			street: undefined,
			state: undefined,
			zipcode: undefined
		},

		validate: function(attrs, options) {
			if (attrs.zipcode !== 16801 && attrs.zipcode !== "16801") {
				return "We're sorry, but at this time we can only deliver to the 16801 area";
			}
		}
	});

	return UserAddress;
});
