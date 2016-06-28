define([
	'backbone'
], function (Backbone) {
	var SiteStatusModel = Backbone.Model.extend({
		urlRoot: '/api/site/status',

		defaults: {
			status: null
		},
		
		isOnline: function() {
			if (this.get('status') === 'online') {
				return true;
			}

			return false;
		}
	});

	return SiteStatusModel;
});
