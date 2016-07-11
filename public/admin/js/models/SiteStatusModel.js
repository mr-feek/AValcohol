define([
	'backbone'
], function (Backbone) {
	var SiteStatusModel = Backbone.Model.extend({
		urlRoot: '/api/site/status',

		defaults: {
			force_offline : null
		},

		isForcedClosed: function() {
			return this.get('force_offline');
		},

		forceStoreClosed: function() {
			this.set('force_offline', 1);
		},

		removeForceStoreClosed: function() {
			this.set('force_offline', 0);
		}
	});

	return SiteStatusModel;
});
