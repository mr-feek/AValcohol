/**
 * Created by Feek on 3/22/16.
 */
define([
	'backbone',
	'shared/js/models/Order',
	'backbone.paginator'
], function (
	Backbone,
	Order
) {
	var collection = Backbone.PageableCollection.extend({
		model: Order,

		state: {
			pageSize: 15
		},

		mode: 'server',

		/**
		 * to support the likes of 'orders/pending' query
		 * @returns {string}
		 */
		url: function() {
			return '/api/admin/orders?' + this.endpoint;
		},

		/**
		 * for calling the likes of orders/pending
		 * @param options
		 */
		initialize: function(models, options) {
			this.endpoint = (options && options.endpoint) ? options.endpoint : '';
		},

		parse: function(response, xhr) {
			return response.orders;
		}
	});

	return collection;
});

