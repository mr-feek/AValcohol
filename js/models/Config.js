define([
	'backbone'
], function (
	Backbone
) {
	/**
	 * A generic model for the front end to store data from the api
	 */
	var config = Backbone.Model.extend({
		urlRoot: '/api/config',

		defaults: {
			blastMessage: 'Ready to crack some brews? Order now to receive within the hour!'
		}
	});

	return config;
});
