define([
	'marionette',
	'foundationEqualizer',
	'views/user-home/ProductView',
	'views/user-home/NoFeaturedProductsView',
	'collections/Products',
	'../../../shared/js/behaviors/CollectionLoadingIndicator'
], function (
	Mn,
	FoundationEqualizer,
	ProductView,
	EmptyView,
	Products,
	CollectionLoadingIndicator
) {
	var ProductsView = Mn.CollectionView.extend({
		childView: ProductView,
		tagName: 'ul',
		className: 'row small-block-grid-1 medium-block-grid-3 large-block-grid-4',
		emptyView: EmptyView,

		attributes: {
			'data-equalizer' : '',
			'data-options' : 'equalize_on_stack: true'
		},

		events: {},

		ui: {},

		behaviors: {
			CollectionLoadingIndicator: {
				behaviorClass: CollectionLoadingIndicator
			}
		},

		/**
		 *
		 * @param options
		 * 		- endpoint (optional)
		 */
		initialize: function (options) {
			this.endpoint = options.endpoint;
			this.collection = new Products([], { endpoint: this.endpoint});
			// pass the collection to the loading indicator
			this.triggerMethod("setCollection", this.collection);
			this.collection.fetch();

			this.listenTo(this, 'render:collection', this.reflowEqualizer); // for reflowing after the collection renders.
			// for some reason onAddChild d oesn't seem to be called after re-rendering
		},

		onShow: function() {
			$(document).foundation({
				equalizer: {
					equalize_on_stack: true
				}
			});
		},

		reflowEqualizer: function() {
			$(document).foundation('equalizer', 'reflow');
		},

		/**
		 * override onAddChild so that once all of the products are rendered
		 * we can reflow the equalizer
		 */
		onAddChild : function() {
			// Check all the models in the collection have their child views rendered
			if (this.children.length == this.collection.length ) {
				this.reflowEqualizer();
			}
		}
	});

	return ProductsView;
});
