define([
	'backbone',
	'backboneRelational'
], function (
	Backbone
) {
	var UserAddress = Backbone.RelationalModel.extend({
		urlRoot: '/api/user/address'
	});

	return UserAddress;
});
