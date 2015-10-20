define([
	'marionette',
	'views/ProductView',
	'views/NoFeaturedProductsView',
	'../collections/Products'
], function (Mn,
			 ProductView,
			 EmptyView,
			 Products
) {
	var ProductsView = Mn.CollectionView.extend({
		childView: ProductView,
		tagName: 'ul',
		className: 'small-block-grid-1 medium-block-grid-3 large-block-grid-5 equalizer',
		emptyView: EmptyView,

		events: {},

		ui: {},

		/**
		 *
		 * @param options
		 * 		- endpoint (optional)
		 */
		initialize: function (options) {
			this.endpoint = options.endpoint;
			this.collection = new Products([], { endpoint: this.endpoint});
			this.collection.fetch();
		}
	});

	return ProductsView;
});
