define([
	'marionette',
	'views/ProductView',
	'views/NoFeaturedProductsView',
	'collections/AllProducts'
], function (Mn,
			 ProductView,
			 EmptyView,
			 AllProducts
) {
	var AllProductsView = Mn.CollectionView.extend({
		childView: ProductView,
		tagName: 'ul',
		className: 'small-block-grid-1 medium-block-grid-3 large-block-grid-5',
		emptyView: EmptyView,

		events: {},

		ui: {},

		initialize: function (options) {
			this.collection = new AllProducts();
			this.collection.fetch();
		},
	});

	return AllProductsView;
});
