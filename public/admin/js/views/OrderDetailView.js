/**
 * Created by Feek on 6/4/16.
 */
define([
	'marionette',
	'views/ProductView',
	'behaviors/Modal',
	'tpl!templates/order-detail.html'
], function (
	Mn,
	ProductView,
	Modal,
	tpl
) {
	var OrderDetailView = Mn.CompositeView.extend({
		template: tpl,
		tagName: 'div',
		className: '',
		childView: ProductView,
		childViewContainer: '.products',

		behaviors: {
			Modal: {
				behaviorClass: Modal
			},
		},

		templateHelpers: function() {
			var user = this.model.get('user');
			var address = this.model.get('address');

			return {
				userName: user.get('profile').first_name + ' ' + user.get('profile').last_name,
				dob: user.get('profile').date_of_birth,
				orderNumber: this.model.get('id'),
				timePlaced: this.model.get('created_at'),
				vendorOrderTotal: this.model.get('vendor_order_total'),
				status: this.model.get('status').get('vendor_status'),
				address: function() {
					return address.get('street') + ' ' + address.get('city') + ' ' + address.get('zipcode')
				}
			}
		},

		initialize: function (options) {
			this.collection = this.model.get('products');
		}
	});

	return OrderDetailView;
});
