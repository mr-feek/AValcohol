define([
	'backbone'
], function (
	Backbone
) {
	var skel = Backbone.Model.extend({
		urlRoot: '/api/'
	});

	return skel;
});
