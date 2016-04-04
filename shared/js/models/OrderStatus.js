/**
 * Created by Feek on 4/4/16.
 */
define([
	'backbone',
	'backboneRelational'
], function (
	Backbone
) {
	var model = Backbone.RelationalModel.extend({
		urlRoot: '/api/order/status'
	});

	return model;
});
