define([
	'backbone',
	'backboneRelational',
], function (
	Backbone
) {
	var UserAddress = Backbone.RelationalModel.extend({
		urlRoot: '/api/address',

		defaults: {
			city: undefined,
			street: undefined,
			state: undefined,
			zipcode: undefined,
			location: {
				latitude: undefined,
				longitude: undefined
			},
			delivery_zone_id: undefined
		},

		parse: function(response, xhr) {
			return response.address;
		},

		initialize: function() {
			_.bindAll(this, 'getDeliveryZone');
		},

		required: ['city', 'street', 'state', 'zipcode'],

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
		},

		/**
		 * retrieves and sets the delivery zone id attribute
		 * @returns {*}
		 */
		getDeliveryZone: function() {
			var promise = $.get(
				'/api/address/delivery_zone', {
					latitude: this.get('location')['latitude'],
					longitude: this.get('location')['longitude']
				}
			);
			promise.done(function(resp) {
				this.set('delivery_zone_id', resp.delivery_zone_id);
			}.bind(this));
			return promise;
		}
	});

	return UserAddress;
});
