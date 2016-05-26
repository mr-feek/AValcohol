define([
	'backbone',
	'backboneRelational',
], function (
	Backbone
) {
	var UserAddress = Backbone.RelationalModel.extend({
		urlRoot: '/api/address',
		propertiesToPersist: ['city', 'street', 'state', 'zipcode', 'delivery_zone_id'], // properties that will be saved in session storage

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
			this.loadFromStorage();
			_.each(this.propertiesToPersist, function(key) {
				this.on('change:' + key, this.attributeChanged, this);
			}, this)
			//this.on('change', this.persist, this); // whenever an attribute is changed throughout the app, persist
		},

		/**
		 * helper function for logic to persist changed attribute
		 */
		attributeChanged: function(model) {
			_.each(model.changed, function(value, key) {
				this.persist(key, value);
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
