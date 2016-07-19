define([
	'backbone',
	'shared/js/models/Product'
], function (
	Backbone,
	Product
) {
	var Cart = Backbone.Collection.extend({
		urlRoot: '/api/',
		model: Product,

		initialize: function() {
			_.bindAll(this, 'calculateSubtotal', 'calculateTax', 'calculateDeliveryFee', 'calculateTotal');
			this.loadFromStorage();
		},

		/*
		|--------------------------------------------------------------------------
		| local storage methods
		|--------------------------------------------------------------------------
		|
		| all methods for the cart interacting / persisting to session storage
		|
		*/

		/**
		 * attempts to load all product IDs from storage into the cart
		 */
		loadFromStorage: function() {
			var products = this.retrieveProductsFromLocalStorage();
			_.each(products, function(p) {
				// _.each includes the commas
				if (p.product_id === ',') { return; }

				var product = Product.findOrCreate({
					id : p.product_id,
					vendor_id : p.vendor_id
				});
				product.fetch();
				this.add(product, { doNotPersistLocally: true }); // don't add more ids to the local storage since they are already there
			}, this);
		},

		/**
		 * returns an array with each index being an object containing product id and vendor id
		 * @return Array
		 */
		retrieveProductsFromLocalStorage: function() {
			var ids = window.sessionStorage.getItem('cart_products_ids');
			var vendor_ids = window.sessionStorage.getItem('cart_products_vendor_ids');
			if (ids && vendor_ids) {
				ids = ids.split(','); // turn csv string into array
				vendor_ids = vendor_ids.split(','); // turn csv string into array

				var products = [];
				for (var i = 0; i < ids.length; i++) {
					products.push({
						product_id: ids[i],
						vendor_id: vendor_ids[i]
					})
				}
				return products;
			}
			return [];
		},

		/**
		 * persists the given product into local storage
		 */
		addProductToLocalStorage: function(product) {
			var products = this.retrieveProductsFromLocalStorage();
			products.push({
				product_id: product.get('id'),
				vendor_id: product.get('pivot').vendor_id
			});

			this._updateLocalStorage(products);
		},

		/**
		 * removes the given product from local storage
		 * @param product
		 */
		removeProductFromLocalStorage: function(product) {
			var products = this.retrieveProductsFromLocalStorage();

			var p = {
				product_id: product.id,
				vendor_id: product.get('pivot').vendor_id
			};

			// find first product match in array (could be more cause of quantity)
			for (var i = 0; i < products.length; i++) {
				var currentProduct = products[i];
				// curentProducts values are stringified ints
				if ((currentProduct.product_id == p.product_id) && (currentProduct.vendor_id == p.vendor_id)) {
					// remove product from array
					products.splice(i, 1);
					break;
				}
			}

			this._updateLocalStorage(products);
		},

		/**
		 * util for saving to local storage
		 * @param products
		 * @private
		 */
		_updateLocalStorage(products) {
			var ids = products.map(function(p) {
				return p.product_id
			});

			var vendor_ids = products.map(function(p) {
				return p.vendor_id
			});

			window.sessionStorage.setItem('cart_products_ids', ids);
			window.sessionStorage.setItem('cart_products_vendor_ids', vendor_ids);
		},

		/*
		|--------------------------------------------------------------------------
		| override of add / remove
		|--------------------------------------------------------------------------
		|
		| Overriding the default cart add / remove collection prototype
		| methods in order to allow for products to have a quantity.
		| THESE METHODS WILL NOT WORK IF THEY ARE PASSED AN ARRAY,
		| the parameter must be a single model.
		|
		*/

		/**
		 * This overrides the default add to allow multiple quantities to be added of an item
		 *
		 * If a model is a duplicate in the cart, it updates the quantity of the original model
		 *
		 * NOTE! this will NOT work if multiple models are passed in, it will only work for adding
		 * one model instance
		 * @param models
		 * @param options
		 * 		- doNotPersistLocally to not persist this model into the local storage
		 */
		add: function(models, options) {
			var origLength = this.length;
			var model = Backbone.Collection.prototype.add.call(this, models, options);
			var newLength = this.length;

			if (origLength == newLength) {
				// it was a dupe
				var quantity = model.get('quantity') + 1;
				model.set('quantity', quantity);
			}

			if (!options.doNotPersistLocally) {
				this.addProductToLocalStorage(model);
			}
		},

		/**
		 * This overrides the default remove to allow quantity stuff
		 *
		 *
		 * NOTE! this will NOT work if multiple models are passed in, it will only work for adding
		 * one model instance
		 * @param models
		 * @param options
		 */
		remove: function(model, options) {
			this.removeProductFromLocalStorage(model);

			if (model.get('quantity') > 1) {
				var quantity = model.get('quantity') - 1;
				model.set('quantity', quantity);
				return;
			}

			Backbone.Collection.prototype.remove.call(this, model, options);
		},

		/*
		|--------------------------------------------------------------------------
		| util methods
		|--------------------------------------------------------------------------
		|
		| dope context
		|
		*/
		calculateSubtotal: function() {
			// loop through products and multiply price * quantity for combined total
			var total = 0;
			_.each(this.models, function(model) {
				total += model.get('pivot').sale_price * model.get('quantity');
			});

			return Number(total).toFixed(2);
		},

		calculateTax: function() {
			var tax = this.calculateSubtotal() * .06;
			return Number(tax).toFixed(2);
		},

		calculateDeliveryFee: function() {
			return Number(5).toFixed(2);
		},

		calculateTotal: function() {
			var total = Number(this.calculateSubtotal()) + Number(this.calculateTax()) + Number(this.calculateDeliveryFee());
			return Number(total).toFixed(2);
		}
	});

	return Cart;
});
