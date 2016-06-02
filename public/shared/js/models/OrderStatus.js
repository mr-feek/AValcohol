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
		urlRoot: '/api/',
		idAttribute: 'order_id',

		url: function() {
			return '/api/vendor/order/' + this.get('order').get('id') + '/status';
		},

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
