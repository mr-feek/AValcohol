define([], function () {
    var Brain = {
        _dataStore: [],

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
         */
        store: function(key, value, options) {
            if (this._dataStore[key] && !options.ignoreWarning) {
                console.warn('overwriting key in the datastore: ' + key);
            }

            this._dataStore[key] = value;
        }
    };

    return Brain;
});
