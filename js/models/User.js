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
			email: null,
			first_name: null,
			last_name: null,
			phone_number: null,
			mvp_user: 1 // this account does NOT need a password, email, etc
		},

		validate: function(attrs, options) {
			var errors = [];
			var defaultMessage = "This field is required";

			if (!attrs.first_name) {
				errors.push({
					attribute: 'first_name',
					message: defaultMessage
				});
			}

			if (!attrs.last_name) {
				errors.push({
					attribute: 'last_name',
					message: defaultMessage
				});
			}

			// some regex i pulled off stack overflow.. should strip anything not a digit
			if (!attrs.phone_number || !attrs.phone_number.match(/\d/g) || attrs.phone_number.match(/\d/g).length !== 10) {
				errors.push({
					attribute: 'phone_number',
					message: 'please enter a ten digit phone number'
				})
			}

			return errors.length > 0 ? errors : null;
		}
	});

	return User;
});
