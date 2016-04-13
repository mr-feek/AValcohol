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
		idAttribute: 'order_id',

		defaults: {
			charge_id : null,
			charge_authorized: null,
			charge_captured: null,
			vendor_status: null,
			delivery_status: null
		}
	});

	return OrderStatus;
});
