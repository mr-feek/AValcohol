define([
	'backbone',
	'models/Product'
], function (
	Backbone,
	Product
) {
	var Products = Backbone.Collection.extend({
		url: '/php/api/product/',
		model: Product,

		/**
		 *
		 * @param options
		 * 		- endpoint: endpoint to add to the url (optional)
		 */
		initialize: function(models, options) {
			if (options.endpoint) {
				this.url += options.endpoint;
			} else {
				this.url += 'all';
			}
		}
	});

	return Products;
});
