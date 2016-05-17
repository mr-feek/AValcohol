/**
 * Created by Feek on 5/17/16.
 */
define([
	'backbone',
	'util/Vent'
], function(
	Backbone,
	Vent
) {
	/**
	 * Helper model for storing state including over page refresh
	 */
	var model = Backbone.Model.extend({
		defaults: {
			currentRoute: null,
			loggedIn: false,
			token: null
		},

		initialize: function() {
			this.setDefaultsFromStorage();
		},

		/**
		 * fetches values saved in storage and sets them to this model
		 */
		setDefaultsFromStorage: function() {
			this.set('token', this.retrieve('token'));
		},

		/**
		 * attempts to log in
		 */
		login: function(data) {
			$.post('/api/auth/login',
				{
					email: data.email,
					password: data.password
				},
				function(result) {
					this.onLoginSuccess(result.token);
				}.bind(this)
			).fail(function(result) {
				// TODO
				alert('incorrect login credentials');
			}.bind(this));
		},

		/*
		* @param token
		*/
		onLoginSuccess: function(token) {
			// add the token to all backbone syncs. Probably should do this a different way, like ajaxSend include if available or something
			Backbone.ajax = function() {
				arguments[0].headers = {
					'Authorization': 'Bearer ' + token,
					'Accept': "application/json"
				};

				return Backbone.$.ajax.apply(Backbone.$, arguments);
			};
			this.persist('token', token);
			Vent.trigger('vendor:authenticated');
		},

		/**
		 * retrieves the key
		 * @param key
		 */
		retrieve: function(key) {
			window.sessionStorage.getItem(key);
		},

		/**
		 * Persists given key value into storage
		 */
		persist: function(key, value) {
			window.sessionStorage.setItem(key, value);
		},

		/**
		 * Removes given key from storage
		 */
		remove: function(key) {
			window.sessionStorage.removeItem(key);
		}
	});

	return model;
});
