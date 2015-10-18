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
		className: 'small-block-grid-1 medium-block-grid-3 large-block-grid-5',
		emptyView: EmptyView,

		events: {},

		ui: {},

		/**
		 *
		 * @param options
		 * 		- endpoint (optional)
		 */
		initialize: function (options) {
			var view = this;
			this.collection = new Products([], { endpoint: options.endpoint});
			this.collection.fetch();
		}
	});

	return ProductsView;
});
