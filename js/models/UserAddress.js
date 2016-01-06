define([
	'backbone',
	'backboneRelational',
], function (
	Backbone
) {
	var UserAddress = Backbone.RelationalModel.extend({
		urlRoot: '/api/user/address',

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
