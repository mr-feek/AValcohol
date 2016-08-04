define([
	'backbone',
	'backboneRelational'
], function (Backbone) {
	var DeliveryZone = Backbone.RelationalModel.extend({
		urlRoot: '/api/',

		defaults: {}
	});

	return DeliveryZone;
});
