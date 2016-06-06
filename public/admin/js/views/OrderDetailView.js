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
				userName: user.get('profile').getFullName(),
				dob: user.get('profile').getDateOfBirth(),
				orderNumber: this.model.get('id'),
				timePlaced: this.model.get('created_at'),
				vendorOrderTotal: this.model.get('vendor_order_total'),
				status: this.model.get('status').get('vendor_status'),
				address: address.getDisplayableAddress()
			}
		},

		initialize: function (options) {
			this.collection = this.model.get('products');
		}
	});

	return OrderDetailView;
});
