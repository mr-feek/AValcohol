define([
	'backbone',
	'backboneRelational'
], function (Backbone) {
	var User = Backbone.RelationalModel.extend({
		urlRoot: 'php/api/user/',

		relations: [{
			type: Backbone.HasMany,
			key: 'user_id',
			relatedModel: 'UserAddress',
			autoFetch: true,
			reverseRelation: {
				key: 'user_id'
			}
		}],

		defaults: {
			mvp_user: 1 // this account does NOT need a password, email, etc
		}
	});

	return User;
});
