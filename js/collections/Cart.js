define([
	'backbone',
	'../../shared/js/models/Product',
	'../../shared/js/util/Vent',
], function (
	Backbone,
	Product,
	Vent
) {
	var Cart = Backbone.Collection.extend({
		urlRoot: 'php/api/',
		model: Product,

		initialize: function() {

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
		}
	});

	return Cart;
});
