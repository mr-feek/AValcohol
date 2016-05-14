/**
 * Created by Feek on 3/16/16.
 */
define([
	'backbone',
	'util/Vent'
], function (
	Backbone,
	Vent
) {
	var Vendor = Backbone.Model.extend({
		urlRoot: '/api/vendor/',

		parse: function(response, xhr) {
			return response.vendor;
		},

		defaults: {
			email: null,
			name: 'Vendor Name',
			password: null,
			autoAcceptOrders: true,
			loggedIn: false,
			token: null
		},

		initialize: function() {
			_.bindAll(this, 'onLoginSuccess');
		},

		validate: function(attrs, options) {
			var errors = [];
			var defaultMessage = "This field is required";

			if (!attrs.email || attrs.email.length < 1) {
				errors.push({
					attribute: 'email',
					message: defaultMessage
				});
			}

			if (!attrs.password || attrs.password.length < 1) {
				errors.push({
					attribute: 'password',
					message: defaultMessage
				});
			}

			return errors.length > 0 ? errors : null;
		},

		/**
		 * attempts to log in
		 */
		login: function() {
			$.post('/api/auth/login',
				{
					email: this.get('email'),
					password: this.get('password')
				},
				function(result) {
					this.onLoginSuccess(result.token);
				}.bind(this)
			).fail(function(result) {
				// TODO
				alert('incorrect login credentials');
			}.bind(this));
		},

		/**
		 *
		 * @param token
		 */
		onLoginSuccess: function(token) {
			this.set('logged_in', true);
			this.set('token', token);

			// add the csrftoken to all backbone syncs
			var oldSync = Backbone.sync;
			Backbone.sync = function(method, model, options){
				options.beforeSend = function(xhr){
					xhr.setRequestHeader('X-CSRFToken', token);
				};
				return oldSync(method, model, options);
			};

			Vent.trigger('vendor:authenticated');
		}
	});

	return Vendor;
});
