define([
	'backbone'
], function (
	Backbone
) {
	var UserAddress = Backbone.Model.extend({
		urlRoot: 'php/api/user/address'
	});

	return UserAddress;
});
