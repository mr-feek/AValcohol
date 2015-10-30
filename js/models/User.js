define([
	'backbone'
], function (Backbone) {
	var User = Backbone.Model.extend({
		urlRoot: 'php/api/user/'
	});

	return User;
});
