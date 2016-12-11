define([], function () {
    var Brain = {
        dataStore: [],

        retrieve: function(key) {
            if (!this.dataStore[key]) {
                console.error('retrieving a non-existant key from the data store: ' + key);
            }

            return this.dataStore[key];
        },

        store: function(key, value) {
            if (this.dataStore[key]) {
                console.warn('overwriting key in the datastore: ' + key);
            }

            this.dataStore[key] = value;
        }
    };

    return Brain;
});
