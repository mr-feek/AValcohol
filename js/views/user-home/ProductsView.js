define([
	'marionette',
	'foundationEqualizer',
	'views/user-home/ProductView',
	'views/user-home/NoFeaturedProductsView',
	'collections/Products',
	'../../../shared/js/behaviors/CollectionLoadingIndicator',
	'App'
], function (
	Mn,
	FoundationEqualizer,
	ProductView,
	EmptyView,
	Products,
	CollectionLoadingIndicator,
	App
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
		 */
		initialize: function (options) {
			this.collection = new Products();
			// pass the collection to the loading indicator
			this.triggerMethod("setCollection", this.collection);
			var address_id = App.user.get('address').get('id');
			this.collection.fetch({ data: $.param({	address_id: address_id })});

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
