define([
	'marionette',
	'foundationEqualizer',
	'views/ProductView',
	'views/NoFeaturedProductsView',
	'../collections/Products'
], function (
	Mn,
	FoundationEqualizer,
	ProductView,
	EmptyView,
	Products
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

		/**
		 *
		 * @param options
		 * 		- endpoint (optional)
		 */
		initialize: function (options) {
			this.endpoint = options.endpoint;
			this.collection = new Products([], { endpoint: this.endpoint});
			this.collection.fetch();
		},

		onShow: function() {
			$(document).foundation({
				equalizer: {
					equalize_on_stack: true,
					before_height_change: function(){
						// do something before the height changes
						//console.log('before');
					},
					after_height_change: function(){
						// do something after the height changes
						//console.log('after');
					}
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
