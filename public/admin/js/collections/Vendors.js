/**
 * Created by Feek on 7/2/16.
 */
define([
	'backbone',
	'shared/js/models/Vendor',
	'backbone.paginator'
], function (
	Backbone,
	Vendor
) {
	var collection = Backbone.PageableCollection.extend({
		model: Vendor,
		url: '/api/vendors',

		state: {
			pageSize: 15,
			sortKey: 'id',
			order: -1
		},

		mode: 'server',

		parseState: function (resp, queryParams, state, options) {
			return {totalRecords: resp.total_count};
		},

		parseRecords: function (response, xhr) {
			return response.vendors;
		}
	});

	return collection;
});