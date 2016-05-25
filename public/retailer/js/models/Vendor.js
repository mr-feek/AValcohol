/**
 * Created by Feek on 3/16/16.
 */
define([
	'backbone'
], function (
	Backbone
) {
	var Vendor = Backbone.Model.extend({
		urlRoot: '/api/vendor',

		parse: function(response, xhr) {
			return response.vendor;
		},

		/**
		 * This should be updated. These are user table props, not vendor table props. Use backbone relational to fix
		 */
		defaults: {
			email: null,
			name: null,
			password: null,
			autoAcceptOrders: true
		},

		initialize: function() { },

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
		}
	});

	return Vendor;
});
