define([
	'backbone'
], function (Backbone) {
	var Product = Backbone.Model.extend({
		urlRoot: '/php/api/'
	});

	return Product;
});
