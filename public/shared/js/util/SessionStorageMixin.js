/**
 * Created by Feek on 12/10/16.
 */
define([
	'underscore',
	'shared/js/util/StorageHelper'
], function(
	_,
	StorageHelper
) {
	return {
		/**
		 * an array of model attributes that should be held in session storage
		 */
		sessionAttributes: [],

		/**
		 * @var StorageHelper
		 */
		storage: null,

		/**
		 * load models attributes from storage and initialize listeners
		 */
		initializeSessionStorage: function() {
			this.storage = new StorageHelper();

			if (!this.storage.storageAvailable) {
				return;
			}

			this.loadFromStorage();
			this.initializeListeners();
		},

		/**
		 * set up listeners for when the model changes
		 */
		initializeListeners: function () {
			_.each(this.sessionAttributes, function(key) {
				this.on('change:' + key, this.attributeChanged, this);
			}, this)
		},

		/**
		 * handler for model persistable property changing
		 * @param model
		 */
		attributeChanged: function (model) {
			_.each(model.changed, function (value, key) {
				// only persist if this key is in the array of keys to persist
				if (_.contains(this.sessionAttributes, key)) {
					// if its an object then change the key name so it can be properly persisted ( delimited by '.')
					if (_.isObject(value)) {
						// cycle through the keys of the supplied object so that they are all stored properly
						_.each(_.keys(value), function (keyName) {
							var nameToPersist = key + '.' + keyName;
							var valueToPersist = value[keyName];
							this.putInStorage(nameToPersist, valueToPersist);
						}, this);
						return;
					}

					this.putInStorage(key, value);
				}
			}, this);
		},

		/**
		 * attempts to load all attributes that should be in storage
		 */
		loadFromStorage: function () {
			_.each(this.sessionAttributes, function (key) {
				this.set(key, this.getFromStorage(key), {silent: true});
			}, this);
		},

		/**
		 * @param key
		 * @returns {*}
		 */
		getFromStorage: function(key) {
			return this.storage.getItem(key);
		},

		/**
		 * @param key
		 * @param value
		 * @returns {*}
		 */
		putInStorage: function(key, value) {
			if (value == null) {
				return this.storage.removeItem(key);
			}
			return this.storage.setItem(key, value);
		}
	};
});
