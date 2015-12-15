define([
	'backbone',
	'backboneRelational'
], function (
	Backbone
) {
	var UserAddress = Backbone.Model.extend({
		urlRoot: '/api/user/address'
	});

	return UserAddress;
});
