/**
 * Created by Feek on 4/12/16.
 */
define([
	'marionette',
	'backbone.poller',
	'util/Vent',
	'collections/Orders',
	'views/OrderOutForDeliveryView',
	'behaviors/CollectionLoading'
], function (
	Mn,
	BackbonePoller,
	Vent,
	Orders,
	OrderOutForDeliveryView,
	CollectionLoadingIndicator
) {
	var view = Mn.CollectionView.extend({
		tagName: 'div',
		className: '',
		childView: OrderOutForDeliveryView,

		behaviors: {
			CollectionLoadingIndicator: {
				behaviorClass: CollectionLoadingIndicator
			}
		},

		events: {},

		ui: {},

		initialize: function (options) {
			this.collection = new Orders([], {	endpoint: '/out'	});

			var options = {
				delay: 30000 // 30 seconds
			};

			this.poller = BackbonePoller.get(this.collection, options);

			// when a signature and picture is collected the order is essentially  delivered, 
			// we need to remove it from this collection to update the ui
			this.listenTo(Vent, 'ordersOutForDeliveryCollection:remove', this.removeModelFromCollection); 
			
			_.bindAll(this, 'removeModelFromCollection');
		},

		onShow: function() {
			this.poller.start();
		},

		onBeforeDestroy: function() {
			this.poller.destroy();
		},
		
		removeModelFromCollection: function(id) {
			this.collection.remove(id);
		}
	});

	return view;
});
