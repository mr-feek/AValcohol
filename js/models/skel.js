define([
	'backbone'
], function(
	Backbone
) {
	var model = Backbone.Model.extend({
		urlRoot: 'php/api/'
	});

	return model;
});
