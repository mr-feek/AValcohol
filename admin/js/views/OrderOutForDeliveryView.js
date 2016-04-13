/**
 * Created by Feek on 3/22/16.
 */
define([
	'marionette',
	'views/ProductView',
	'tpl!templates/order-out-for-delivery.html'
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

		events: {
			'click @ui.reject' : 'rejectOrder',
			'click @ui.accept' : 'acceptOrder'
		},

		ui: {
			reject: '.reject',
			accept: '.accept'
		},

		initialize: function (options) {
			this.collection = this.model.get('products');
		},

		acceptOrder: function(e) {
			e.preventDefault();
		}
	});

	return view;
});
