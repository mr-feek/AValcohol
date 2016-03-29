define([
	'backbone',
	'backboneRelational',
], function (
	Backbone
) {
	var UserAddress = Backbone.RelationalModel.extend({
		urlRoot: '/api/address',

		defaults: {
			id: 1, // temp
			city: undefined,
			street: undefined,
			state: undefined,
			zipcode: undefined
		},

		parse: function(response, xhr) {
			return response.address;
		},

		required: ['city', 'street', 'state'], // taking out zipcode for now since it has additional validator

		validate: function(attrs, options) {
			var errors = [];

			_.each(this.required, function(attribute) {
				if (!attrs[attribute]) {
					errors.push({
						attribute: attribute,
						message: "This field is required."
					});
				}
			});

			if (!attrs.zipcode || (attrs.zipcode !== 16801 && attrs.zipcode !== "16801")) {
				errors.push({
					attribute: 'zipcode',
					message: "We're sorry, but at this time we can only deliver to the 16801 area"
				});
			}

			return errors.length > 0 ? errors : null;
		}
	});

	return UserAddress;
});
