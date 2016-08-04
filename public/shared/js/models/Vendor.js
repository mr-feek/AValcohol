/**
 * Created by Feek on 3/16/16.
 */
define([
	'backbone',
	'shared/js/models/VendorHours',
	'shared/js/models/DeliveryZone',
	'backboneRelational'
], function (
	Backbone,
	VendorHours,
	DeliveryZone
) {
	var Vendor = Backbone.RelationalModel.extend({
		urlRoot: '/api/vendor',

		relations: [
			{
				type: Backbone.HasMany,
				key: 'hours',
				relatedModel: VendorHours,
				includeInJSON: true
			},
			{
				type: Backbone.HasOne,
				key: 'delivery_zone',
				relatedModel: DeliveryZone,
				includeInJSON: false
			}
		],

		parse: function(response, xhr) {
			if (response.vendor) {
				return response.vendor;
			}
			return response;
		},

		defaults: {
			name: null,
			address: null,
			phone_number: null
		},

		initialize: function() { },

		validate: function(attrs, options) {
			var errors = [];
			var defaultMessage = "This field is required";

			if (!attrs.email || attrs.email.length < 1) {
				errors.push({
					attribute: 'email',
					message: defaultMessage
				});
			}

			if (!attrs.password || attrs.password.length < 1) {
				errors.push({
					attribute: 'password',
					message: defaultMessage
				});
			}

			return errors.length > 0 ? errors : null;
		}
	});

	return Vendor;
});
