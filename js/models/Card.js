define([
	'backbone',
	'backboneRelational'
], function (
	Backbone
) {
	var model = Backbone.RelationalModel.extend({
		urlRoot: null, // NO BACKEND INTERACTION

		defaults: {
			last_four : null,
			token: null
		},
	});

	return model;
});
