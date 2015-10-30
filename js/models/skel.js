define([
	'backbone'
], function (
	Backbone
) {
	var skel = Backbone.Model.extend({
		urlRoot: 'php/api/'
	});

	return skel;
});
