/**
 * Created by Feek on 4/12/16.
 */
define([
	'marionette',
	'backbone.poller',
	'collections/Orders',
	'views/OrderOutForDeliveryView',
	'../../../shared/js/behaviors/CollectionLoadingIndicator'
], function (
	Mn,
	BackbonePoller,
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
		},

		onRender: function() {
			this.poller.start();
		}
	});

	return view;
});
