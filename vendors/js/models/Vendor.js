/**
 * Created by Feek on 3/16/16.
 */
define([
	'backbone'
], function (
	Backbone
) {
	var Vendor = Backbone.Model.extend({
		urlRoot: '/api/vendor/',

		parse: function(response, xhr) {
			return response.vendor;
		},

		defaults: {
			email: null,
			name: 'Vendor Name',
			password: null
		},

		initialize: function() {
		},

		validate: function(attrs, options) {
			var errors = [];
			var defaultMessage = "This field is required";

			if (!attrs.username || attrs.username.length < 1) {
				errors.push({
					attribute: 'username',
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

		login: function() {
			$.post('/api/vendor/login',
				{
					username: this.get('username'),
					password: this.get('password')
				},
				function(result) {
					debugger;
				}
			).fail(function(result) {

			});
		}
	});

	return Vendor;
});
