/**
 * Created by Feek on 3/30/16.
 */
define([
	'marionette',
	'tpl!templates/product.html'
], function (
	Mn,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,
		tagName: 'div',
		className: 'row product',

		templateHelpers: function() {
			return {
				'product_vendor_price' : this.model.get('pivot').product_vendor_price
			}
		},

		events: {},

		ui: {},

		initialize: function (options) {
		}
	});

	return view;
});
