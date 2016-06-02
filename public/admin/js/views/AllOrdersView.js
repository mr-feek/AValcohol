/**
 * Created by Feek on 6/2/16.
 */
define([
	'marionette',
	'backbone.poller',
	'collections/Orders',
	'views/ReadyOrderView', // temp
	'tpl!templates/all-orders.html'
], function (
	Mn,
	BackbonePoller,
	Orders,
	ReadyOrderView,
	tpl
) {
	var AllOrdersView = Mn.CollectionView.extend({
		template: tpl,
		childView: ReadyOrderView,

		templateHelpers: function () {
			return {}
		},
		 /*
		behaviors: {
			Modal: {
				behaviorClass: Modal
			},
		},
		*/

		events: {
		},

		ui: {
		},

		initialize: function (options) {
			this.collection = new Orders([], {	endpoint: 'all'	});

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

	return AllOrdersView;
});
