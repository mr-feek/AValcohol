define([
	'backbone',
	'backboneRelational',
], function (
	Backbone
) {
	var UserAddress = Backbone.RelationalModel.extend({
		urlRoot: '/api/address',
		propertiesToPersist: ['city', 'street', 'state', 'zipcode', 'delivery_zone_id', 'location'], // properties that will be saved in session storage

		defaults: {
			city: null,
			street: null,
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
			_.bindAll(this, 'getDeliveryZone');
			
			// flag for if this address should be stored / load from storage. IE this should only be used for the local user, not for loading addresses in orders
			if (options.useStorage) {
				this.loadFromStorage();
				_.each(this.propertiesToPersist, function(key) {
					this.on('change:' + key, this.attributeChanged, this);
				}, this)
			}
		},

		getDisplayableAddress: function() {
			return this.get('street') + ' ' + this.get('city') + ' ' + this.get('zipcode');
		},

		/**
		 * helper function for logic to persist changed attribute
		 */
		attributeChanged: function(model) {
			_.each(model.changed, function(value, key) {
				// only persist if this key is in the array of keys to persist
				if (_.contains(this.propertiesToPersist, key)) {
					// if its an object then change the key name so it can be properly persisted ( delimited by '.')
					if (_.isObject(value)) {
						// cycle through the keys of the supplied object so that they are all stored properly
						_.each(_.keys(value), function(keyName) {
							var nameToPersist = key + '.' + keyName;
							var valueToPersist = value[keyName];
							this.persist(nameToPersist, valueToPersist);
						}, this);
						return;
					}

					this.persist(key, value);
				}
			}, this);
		},

		/**
		 * attempts to load all attributes that should be in storage
		 */
		loadFromStorage: function() {
			_.each(this.propertiesToPersist, function(key) {
				this.set(key, this.retrieve(key), {	silent: true });
			}, this);
		},

		/**
		 * retrieves the key
		 * @param key
		 * @return value
		 */
		retrieve: function(key) {
			// location needs to be handled differently since it is an object
			if (key === 'location') {
				var longitude = window.sessionStorage.getItem('location.longitude');
				var latitude = window.sessionStorage.getItem('location.latitude');

				return {
					longitude: longitude,
					latitude: latitude
				}
			}

			return window.sessionStorage.getItem(key);
		},

		/**
		 * Persists given key value into storage
		 */
		persist: function(key, value) {
			window.sessionStorage.setItem(key, value);
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
				if (resp.delivery_zone_id) {
					this.set('delivery_zone_id', resp.delivery_zone_id);
				}
			}.bind(this));
			return promise;
		}
	});

	return UserAddress;
});
