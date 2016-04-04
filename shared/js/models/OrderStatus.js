/**
 * Created by Feek on 4/4/16.
 */
define([
	'backbone',
	'backboneRelational'
], function (
	Backbone
) {
	var OrderStatus = Backbone.RelationalModel.extend({
		urlRoot: '/api/order/status',

		initialize: function() {
			Backbone.Model.prototype.idAttribute = 'order_id'
		}
	});

	return OrderStatus;
});
