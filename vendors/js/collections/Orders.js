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
	var collection =Backbone.Collection.extend({
		url: '/api/vendor/orders',
		model: Order,

		parse: function(response, xhr) {
			return response.orders;
		}
	});

	return collection;
});

