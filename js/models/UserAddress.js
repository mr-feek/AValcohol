define([
	'backbone',
	'backboneRelational',
	'models/User'
], function (
	Backbone
) {
	var UserAddress = Backbone.RelationalModel.extend({
		urlRoot: '/api/user/address',

		relations: [
			{
				type: Backbone.HasOne,
				key: 'user',
				relatedModel: 'User',
				includeInJSON: false
			}
		],
	});

	return UserAddress;
});
