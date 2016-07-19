define([
	'backbone'
], function (Backbone) {
	var SiteStatusModel = Backbone.Model.extend({
		urlRoot: '/api/site/status',

		defaults: {
			online: null,
			updatable: false
		},

		isOnline: function() {
			if (this.get('online') == true) {
				return true;
			}

			return false;
		},

		setOnline: function() {
			this.set('online', 1);
		},

		setOffline: function() {
			this.set('online', 0);
		},

		adminCanUpdate: function() {
			return this.get('updatable');
		}
	});

	return SiteStatusModel;
});
