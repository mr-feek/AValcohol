define([
    ''
], function () {
    var Brain = {
        store: [],

        retrieve: function(key) {
            if (!this.store[key]) {
                console.error('retrieving a non-existant key from the data store: ' + key);
            }
            return this.store[key];
        },

        persist: function(key, value) {
            this.store[key] = value;
        }
    };

    return Brain;
});
