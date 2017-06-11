/**
 * this is a simple datastore for all backbone models used throughout the application
 * lifecycle
 */

define([], function () {
    var Brain = {
        _dataStore: {},

        retrieve: function(key) {
            if (!this._dataStore[key]) {
                console.error('retrieving a non-existant key from the data store: ' + key);
            }

            return this._dataStore[key];
        },

		/**
         *
         * @param key
         * @param value
         * @param options
         *  - ignoreWarning
         *  @returns {Brain}
         */
        store: function(key, value, options) {
            options = options || {};
            if (this._dataStore[key] && !options.ignoreWarning) {
                console.warn('overwriting key in the datastore: ' + key);
            }

            this._dataStore[key] = value;

            return this;
        },

		/**
         * overwrites the given key in the store, locally deleting the model
         *
         * @param key
         * @param value
         * @param options
         * @returns {*}
         */
        overwrite: function(key, value, options) {
            if (!this.retrieve(key)) {
                // treat it as a store
                return this.store(key, value, options)
            }

            return this.forget(key).store(key, value, options);
        },

		/**
		 * forgets a model from the store, calling a local delete function if it exists
         * @param key
         * @returns {Brain}
         */
        forget: function(key) {
            var old = this.retrieve(key);

            // delete the old model locally
            if (typeof old.destroyClientSide === 'function') {
                old.destroyClientSide();
            }

            // clear the store
            delete this._dataStore[key];

            return this;
        }
    };

    return Brain;
});
