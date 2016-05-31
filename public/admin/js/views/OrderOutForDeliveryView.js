/**
 * Created by Feek on 3/22/16.
 */
define([
	'marionette',
	'shared/js/models/OrderDeliveryDetails',
	'views/ProductView',
	'views/CustomerInfoCollection/ParentView',
	'App',
	'tpl!templates/order-out-for-delivery.html'
], function (
	Mn,
	OrderDeliveryDetails,
	ProductView,
	CustomerInfoCollectionParentView,
	app,
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
			'click @ui.deliver' : 'showDeliveryView'
		},

		ui: {
			deliver: '.deliver'
		},

		initialize: function (options) {
			this.collection = this.model.get('products');
		},

		showDeliveryView: function(e) {
			app.rootView.getRegion('modalRegion').show(new CustomerInfoCollectionParentView({
				model: OrderDeliveryDetails.findOrCreate({
					order: this.model
				})
			}));
		}
	});

	return view;
});
