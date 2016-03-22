/**
 * Created by Feek on 3/22/16.
 */
define([
	'marionette',
	'collections/Orders',
	'../../../shared/js/behaviors/CollectionLoadingIndicator',
	'tpl!templates/orders.html'
], function (
	Mn,
	Orders,
	CollectionLoadingIndicator,
	tpl
) {
	var view = Mn.CompositeView.extend({
		template: tpl,
		tagName: 'div',
		className: '',

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
			this.collection.fetch();
		}
	});

	return view;
});
