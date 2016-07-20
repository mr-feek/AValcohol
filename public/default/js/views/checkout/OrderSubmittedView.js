/**
 * Created by Feek on 1/24/16.
 */
define([
	'marionette',
	'tpl!templates/checkout/order-submitted.html'
], function (
	Mn,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,
		tagName: 'div',
		className: '',

		templateHelpers: function() {
			var order = this.model.attributes;
			var user = this.model.get('user').attributes;
			var address = this.model.get('address');
			var products = this.model.get('products');

			return {
				order: order,
				user: user,
				address: address,

				getProductInfo: function() {
					var html = '';
					products.each(function(product) {

						html += product.get('name') + ',';
					});

					return html;
				}
			}
		},

		events: {},

		ui: {},

		initialize: function (options) {
		}
	});

	return view;
});
