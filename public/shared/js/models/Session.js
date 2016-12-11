/**
 * Created by Feek on 5/17/16.
 */
define([
	'backbone',
	'shared/js/util/Vent',
	'shared/js/util/SessionStorageMixin'
], function(
	Backbone,
	Vent,
	SessionStorageMixin
) {
	var model = Backbone.Model.extend(_.extend(SessionStorageMixin, {
		/**
		 * an array of model attributes that should be held in session storage
		 */
		sessionAttributes: [
			'token'
		],

		defaults: {
			email: null,
			password: null,
			loggedIn: false,
			token: null
		},

		initialize: function() {
			this.initializeSessionStorage();
			this.setTokenOnRequests();
		},

		validate: function(attrs, options) {
			var errors = [];
			var defaultMessage = "This field is required";

			if (!attrs.password) {
				errors.push({
					attribute: 'password',
					message: defaultMessage
				})
			}

			if (!attrs.email) {
				errors.push({
					attribute: 'email',
					message: 'please enter a valid email address'
				});
			}

			return errors.length > 0 ? errors : null;
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
		 * attempts to log in
		 */
		login: function(data) {
			this.trigger('request');
			$.post('/api/auth/login',
				{
					email: data.email,
					password: data.password
				},
				function(result) {
					this.trigger('sync');
					this.onLoginSuccess(result.token);
				}.bind(this)
			).fail(function(result) {
				this.trigger('error');
			}.bind(this));
		},

		logout: function() {
			this.set('token', null);
			this.set('loggedIn', false);
		},

		/*
		* @param token
		*/
		onLoginSuccess: function(token) {
			this.set('token', token);
			Vent.trigger('user:authenticated');
		}
	}));

	return model;
});
