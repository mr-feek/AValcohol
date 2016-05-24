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
			this.setTokenOnRequests();
		},

		/**
		 * if a token is present, this will ensure it is included in every ajax request
		 */
		setTokenOnRequests: function() {
			// Add the token to ALL backbone requests! those that don't need it will just ignore it on the backend
			Backbone.ajax = function() {
				arguments[0].headers = {
					'Authorization': 'Bearer ' + this.get('token'),
					'Accept': "application/json"
				};

				return Backbone.$.ajax.apply(Backbone.$, arguments);
			}.bind(this);
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
					// fetch vendor details
					
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
			this.set('token', token);
			this.persist('token', token);
			Vent.trigger('vendor:authenticated');
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

		/**
		 * Removes given key from storage
		 */
		remove: function(key) {
			window.sessionStorage.removeItem(key);
		}
	});

	return model;
});
