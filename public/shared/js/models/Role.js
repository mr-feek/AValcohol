define([
	'backbone',
	'backboneRelational'
], function (Backbone) {
	var Role = Backbone.RelationalModel.extend({
		urlRoot: '/api/',

		defaults: {}
	});

	return Role;
});
