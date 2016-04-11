define([
	'backbone',
	'../models/Product'
], function (
	Backbone,
	Product
) {
	var Cart = Backbone.Collection.extend({
		urlRoot: '/api/',
		model: Product,

		initialize: function() {
			_.bindAll(this, 'calculateSubtotal');
		},

		/**
		 * This overrides the default add to allow multiple quantities to be added of an item
		 *
		 * If a model is a duplicate in the cart, it updates the quantity of the original model
		 *
		 * NOTE! this will NOT work if multiple models are passed in, it will only work for adding
		 * one model instance
		 * @param models
		 * @param options
		 */
		add: function(models, options) {
			var origLength = this.length;
			// call real add
			var model = Backbone.Collection.prototype.add.call(this, models, options);

			var newLength = this.length;

			if (origLength == newLength) {
				// it was a dupe
				var quantity = model.get('quantity') + 1;
				model.set('quantity', quantity);
			}
		},

		// util methods
		calculateSubtotal: function() {
			// loop through products and multiply price * quantity for combined total
			var total = 0;
			
			_.each(this.models, function(model) {
				total += model.get('sale_price') * model.get('quantity');
			});

			return Number(total).toFixed(2);
		},
	});

	return Cart;
});
