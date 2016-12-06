define([
    ''
], function () {
    var Brain = {
        memory: [],

        retrieve: function(key) {
            if (!this.store[key]) {
                console.error('retrieving a non-existant key from the data store: ' + key);
            }
            return this.store[key];
        },

        store: function(key, value) {
            this.memory[key] = value;
        }
    };

    return Brain;
});
