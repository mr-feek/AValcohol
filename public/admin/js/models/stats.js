define([
	'backbone'
], function (Backbone) {
	var stats = Backbone.Model.extend({
		urlRoot: '/api/admin/stats',

		defaults: {
			'total_sales' : null,
			'average_sale_amount' : null,
			'total_aggregated_sales_amount' : null,
			'sales_today' : null
		}
	});

	return stats;
});
