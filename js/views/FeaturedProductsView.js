define([
	'marionette',
	'views/ProductView',
	'collections/FeaturedProducts',
	'views/NoFeaturedProductsView'
], function (
	Mn,
	ProductView,
	FeaturedProducts,
	EmptyView
) {
	var FeaturedProductsView = Mn.CollectionView.extend({
		tagName: 'ul',
		className: 'small-block-grid-1 medium-block-grid-3 large-block-grid-5',
		emptyView: EmptyView,
		childView: ProductView,
		events: {},

		ui: {},

		initialize: function (options) {
			this.collection = new FeaturedProducts();
			this.collection.fetch();
		}
	});

	return FeaturedProductsView;
});
