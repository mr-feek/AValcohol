/**
 * Created by Feek on 3/22/16.
 */
define([
	'backbone',
	'../../../shared/js/models/Order'
], function (
	Backbone,
	Order
) {
	var collection = Backbone.Collection.extend({
		model: Order,
		/**
		 * to support the likes of 'orders/pending' query
		 * @param endpoint
		 * @returns {string}
		 */
		url: function(endpoint) {
			return '/api/admin/orders' + this.endpoint;
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

