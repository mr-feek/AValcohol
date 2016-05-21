/**
 * Created by Feek on 3/22/16.
 */
define([
	'marionette',
	'backbone.poller',
	'collections/Orders',
	'views/NoOrdersView',
	'views/OrderView',
	'behaviors/CollectionLoading'
], function (
	Mn,
	BackbonePoller,
	Orders,
	NoOrdersView,
	OrderView,
	CollectionLoadingIndicator
) {
	var view = Mn.CollectionView.extend({
		tagName: 'div',
		className: '',
		childView: OrderView,
		emptyView: NoOrdersView,

		behaviors: {
			CollectionLoadingIndicator: {
				behaviorClass: CollectionLoadingIndicator
			}
		},

		events: {},

		ui: {},

		initialize: function (options) {
			this.collection = new Orders([], {	endpoint: '/pending'	});
			//this.triggerMethod("setCollection", this.collection);

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
