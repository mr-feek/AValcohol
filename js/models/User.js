define([
	'backbone',
	'backboneRelational'
], function (Backbone) {
	var User = Backbone.Model.extend({
		urlRoot: '/api/user/',



		defaults: {
			mvp_user: 1 // this account does NOT need a password, email, etc
		}
	});

	return User;
});
