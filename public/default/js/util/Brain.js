define([
    ''
], function () {
    var Brain = {
        dataStore: [],

        retrieve: function(key) {
            if (!this.dataStore[key]) {
                console.error('retrieving a non-existant key from the data store: ' + key);
            }
            return this.dataStore[key];
        },

        store: function(key, value) {
            this.dataStore[key] = value;
        }
    };

    return Brain;
});
