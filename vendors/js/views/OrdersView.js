/**
 * Created by Feek on 3/22/16.
 */
define([
	'marionette',
	'collections/Orders',
	'views/OrderView',
	'../../../shared/js/behaviors/CollectionLoadingIndicator'
], function (
	Mn,
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
			this.collection = new Orders();
			this.triggerMethod("setCollection", this.collection);
		},

		onRender: function() {
			this.collection.fetch();
		}
	});

	return view;
});
