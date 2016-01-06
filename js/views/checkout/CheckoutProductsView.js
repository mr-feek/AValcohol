define([
	'marionette',
	'views/CartProductView',
	'views/EmptyCartView'
], function (
	Mn,
	CartProductView,
	EmptyCartView
) {
	var CheckoutProductsView = Mn.CollectionView.extend({
		tagName: 'div',
		className: '',
		childView: CartProductView,
		emptyView: EmptyCartView,

		templateHelpers: function() {
			var view = this;

			return {

			}
		},

		events: {},

		ui: {},

		initialize: function (options) {
		}
	});

	return CheckoutProductsView;
});
/**
 * Created by Feek on 11/2/15.
 */
