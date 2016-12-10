define([
	'backbone',
	'shared/js/util/SessionStorageMixin',
	'backboneRelational'
], function (
	Backbone,
	SessionStorageMixin
) {
	var UserAddress = Backbone.RelationalModel.extend(_.extend(SessionStorageMixin, {
		urlRoot: '/api/address',
		sessionAttributes: ['city', 'street', 'state', 'zipcode', 'delivery_zone_id', 'location', 'apartment_number'],

		defaults: {
			city: null,
			street: null,
			apartment_number: null,
			state: null,
			zipcode: null,
			location: {
				latitude: null,
				longitude: null
			},
			delivery_zone_id: null
		},

		parse: function(response, xhr) {
			return response.address;
		},

		initialize: function(attributes, options) {
			options = options || {};
			
			_.bindAll(this, 'getDeliveryZone');

			// flag for if this address should be stored / load from storage. IE this should only be used for the local user, not for loading addresses in orders
			if (options.useStorage) {
				this.initializeSessionStorage();
			}
		},

		getDisplayableAddress: function() {
			var apartmentNumberDisplay = this.get('apartment_number');

			if (apartmentNumberDisplay) {
				apartmentNumberDisplay = ' Apt. ' + apartmentNumberDisplay;
			}

			return this.get('street') + ' ' + this.get('city') + ' ' + this.get('zipcode') + apartmentNumberDisplay;
		},

		required: ['city', 'street', 'state', 'zipcode'],

		validate: function(attrs, options) {
			var errors = [];

			_.each(this.required, function(attribute) {
				if (!attrs[attribute]) {
					var message = 'This field is required.';

					if (attribute == 'zipcode') {
						message = 'A valid US zipcode is required to use our service.';
					}

					errors.push({
						attribute: attribute,
						message: message
					});
				}
			});

			return errors.length > 0 ? errors : null;
		},

		/**
		 * retrieves and sets the delivery zone id attribute
		 * @returns {*}
		 */
		getDeliveryZone: function() {
			this.trigger('request');

			var promise = $.get(
				'/api/address/delivery_zone', {
					latitude: this.get('location')['latitude'],
					longitude: this.get('location')['longitude'],
					street: this.get('street')
				}
			);

			promise.done(function(resp) {
				this.trigger('sync');
				if (resp.delivery_zone_id) {
					this.set('delivery_zone_id', resp.delivery_zone_id);
				}
			}.bind(this));

			promise.fail(function(resp) {
				this.trigger('error');
			}.bind(this));

			return promise;
		},

		/**
		 * retrieves the key. overrides session mixin
		 * @param key
		 * @return value
		 */
		getFromStorage: function(key) {
			// location needs to be handled differently since it is an object
			if (key === 'location') {
				var longitude = this.storage.getItem('location.longitude');
				var latitude = this.storage.getItem('location.latitude');

				return {
					longitude: longitude,
					latitude: latitude
				}
			}

			return this.storage.getItem(key);
		}
	}));

	return UserAddress;
});
