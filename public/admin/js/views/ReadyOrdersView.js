/**
 * Created by Feek on 3/22/16.
 */
define([
	'marionette',
	'backbone.poller',
	'collections/Orders',
	'views/ReadyOrderView',
	'behaviors/LoadingIndicator'
], function (
	Mn,
	BackbonePoller,
	Orders,
	ReadyOrderView,
	CollectionLoadingIndicator
) {
	var view = Mn.CollectionView.extend({
		tagName: 'div',
		className: '',
		childView: ReadyOrderView,

		behaviors: {
			CollectionLoadingIndicator: {
				behaviorClass: CollectionLoadingIndicator
			}
		},

		events: {},

		ui: {},

		initialize: function (options) {
			this.collection = new Orders([], {	endpoint: 'ready'	});
			this.triggerMethod('setListener', this.collection);
			
			var options = {
				delay: 30000 // 30 seconds
			};

			this.poller = BackbonePoller.get(this.collection, options);
		},

		onShow: function() {
			this.poller.start();
		},

		onBeforeDestroy: function() {
			this.poller.destroy();
		}
	});

	return view;
});
