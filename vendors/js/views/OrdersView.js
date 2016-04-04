/**
 * Created by Feek on 3/22/16.
 */
define([
	'marionette',
	'backbone.poller',
	'collections/Orders',
	'views/OrderView',
	'../../../shared/js/behaviors/CollectionLoadingIndicator'
], function (
	Mn,
	BackbonePoller,
	Orders,
	OrderView,
	CollectionLoadingIndicator
) {
	var view = Mn.CollectionView.extend({
		tagName: 'div',
		className: '',
		childView: OrderView,

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
				delay: 30000, // 30 seconds
				merge: false // this is triggering a reload, figure out why (but the data should never be changing anyway)
			};
			this.poller = BackbonePoller.get(this.collection, options);
		},

		onRender: function() {
			this.poller.start();
		}
	});

	return view;
});
