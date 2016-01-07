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
			var errors = [];
			if (attrs.zipcode !== 16801 && attrs.zipcode !== "16801") {
				errors.push({
					attribute: attrs.zipcode,
					message: "We're sorry, but at this time we can only deliver to the 16801 area"
				});
			}

			return errors.length > 0 ? errors : null;
		}
	});

	return UserAddress;
});
