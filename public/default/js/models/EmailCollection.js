define([
	'backbone'
], function(
	Backbone
) {
	var model = Backbone.Model.extend({
		urlRoot: '/api/email/collect',

		defaults: {
			name: null,
			email: null
		},

		validate: function (attrs, options) {
			var errors = [];
			var defaultMessage = "This field is required";
			_.each(attrs, function(value, key) {
				if (!value) {
					errors.push({
						attribute: key,
						message: defaultMessage
					});
				}
			});

			return errors.length > 0 ? errors : null;
		}
	});

	return model;
});
