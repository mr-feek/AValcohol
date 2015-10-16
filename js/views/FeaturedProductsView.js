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
