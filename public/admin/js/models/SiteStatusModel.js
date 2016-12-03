define([
	'backbone'
], function (Backbone) {
	var SiteStatusModel = Backbone.Model.extend({
		urlRoot: '/api/site/status',

		defaults: {
			admin_force_offline : null
		},

		isForcedClosed: function() {
			return this.get('admin_force_offline');
		},

		forceStoreClosed: function() {
			this.set('admin_force_offline', 1);
		},

		removeForceStoreClosed: function() {
			this.set('admin_force_offline', 0);
		},

		parse: function(response) {
			return response.site_status;
		}
	});

	return SiteStatusModel;
});
