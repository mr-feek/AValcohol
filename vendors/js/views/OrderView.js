/**
 * Created by Feek on 3/22/16.
 */
define([
	'marionette',
	'views/ProductView',
	'tpl!templates/order.html'
], function (
	Mn,
	ProductView,
	tpl
) {
	var view = Mn.CompositeView.extend({
		template: tpl,
		tagName: 'div',
		className: '',
		childView: ProductView,
		childViewContainer: '.products',

		templateHelpers: function() {
			var user = this.model.get('user');
			return {
				userName: user.get('profile').first_name + ' ' + user.get('profile').last_name,
				dob: user.get('dob'),
				orderNumber: this.model.get('id'),
				timePlaced: this.model.get('created_at'),
				vendorOrderTotal: this.model.get('vendor_order_total')
			}
		},

		events: {},

		ui: {},

		initialize: function (options) {
			this.collection = this.model.get('products');
		}
	});

	return view;
});
