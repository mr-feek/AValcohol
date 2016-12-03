define([
	'backbone'
], function (Backbone) {
	var BlacklistedAddress = Backbone.Model.extend({
		urlRoot: '/api/',

		defaults: {
			reason: null
		}
	});

	return BlacklistedAddress;
});
