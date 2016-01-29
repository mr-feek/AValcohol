define([
	'backbone',
	'backbone.wreqr'
], function(
	Backbone
) {
	var Vent = new Backbone.Wreqr.EventAggregator();

	return Vent;
});
