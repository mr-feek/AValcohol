define([
	'backbone'
], function (Backbone) {
	var SiteStatusModel = Backbone.Model.extend({
		urlRoot: '/api/site/status',

		defaults: {
			online: null
		},

		parse: function(response) {
			return response.status;
		},

		isOnline: function() {
			if (this.get('online') === 1) {
				return true;
			}

			return false;
		},

		setOnline: function() {
			this.set('online', 1);
		},

		setOffline: function() {
			this.set('online', 0);
		}
	});

	return SiteStatusModel;
});
