/**
 * Created by Feek on 7/29/16.
 */
define([
	'backbone',
	'marionette'
], function (
	Backbone,
	Marionette
) {
	var StorageHelper = Marionette.Object.extend({
		storageAvailable: null,

		initialize: function(options) {
			this.storageAvailable = this.checkIfStorageIsAvailable();
		},

		/**
		 * determine whether or not we have access to storage on this device
		 * @returns {boolean}
		 */
		checkIfStorageIsAvailable: function () {
			try {
				var storage = window['sessionStorage'],
					x = '__storage_test__';
				storage.setItem(x, x);
				storage.removeItem(x);
				return true;
			}
			catch (e) {
				return false;
			}
		},

		getItem: function (key) {
			if (this.storageAvailable) {
				return window.sessionStorage.getItem(key);
			}
		},

		setItem: function(key, value) {
			if (this.storageAvailable) {
				return window.sessionStorage.setItem(key, value);
			}
		},

		removeItem: function(key) {
			if (this.storageAvailable) {
				return window.sessionStorage.removeItem(key);
			}
		}
	});

	return StorageHelper;

});

