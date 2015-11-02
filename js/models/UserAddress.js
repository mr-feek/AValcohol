define([
	'backbone',
	'backboneRelational'
], function (
	Backbone
) {
	var UserAddress = Backbone.RelationalModel.extend({
		urlRoot: 'php/api/user/address'
	});

	return UserAddress;
});
