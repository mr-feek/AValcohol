define([
	'marionette',
	'foundationEqualizer',
	'views/user-home/ProductView',
	'collections/Products',
	'behaviors/LoadingIndicator',
	'App'
], function (
	Mn,
	FoundationEqualizer,
	ProductView,
	Products,
	CollectionLoadingIndicator,
	App
) {
	var ProductsView = Mn.CollectionView.extend({
		childView: ProductView,
		tagName: 'ul',
		className: 'row small-up-2 medium-up-3 large-up-4',
		attributes: {
			'data-equalizer' 		: '',
			'data-equalize-by-row'	: 'true'
		},
		equalizerInitialized: false,

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
			this.triggerMethod('setListener', this.collection);
			var delivery_zone_id = App.user.get('address').get('delivery_zone_id');

			if (App.config.get('isClosed') === true) {
				this.collection.fetch({ data: $.param({
					delivery_zone_id: delivery_zone_id,
					includeClosed: true // since the store is closed, grab the closed ones anway
				})});
			} else {
				this.collection.fetch({ data: $.param({	delivery_zone_id: delivery_zone_id })});
			}

			this.listenTo(this, 'render:collection', this.reflowEqualizer); // for reflowing after the collection renders.
			// for some reason onAddChild doesn't seem to be called after re-rendering
		},

		reflowEqualizer: function() {
			//Foundation.reInit('equalizer');
			Foundation.reInit(this.$el);
		},

		onRender: function() {
			if (this.equalizerInitialized) {
				return;
			}

			var elem = new Foundation.Equalizer(this.$el);
			this.equalizerInitialized = true;
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
