/**
 * Created by Feek on 3/16/16.
 */
define([
	'backbone',
	'backbone.wreqr'
], function (Backbone) {
	var Vent = new Backbone.Wreqr.EventAggregator();

	return Vent;
});
