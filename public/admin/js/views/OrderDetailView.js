/**
 * Created by Feek on 6/4/16.
 */
define([
	'marionette',
	'views/ProductView',
	'behaviors/Modal',
	'shared/js/models/OrderDeliveryDetails',
	'tpl!templates/order-detail.html'
], function (
	Mn,
	ProductView,
	Modal,
	DeliveryDetails,
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

		ui: {
			'showPictureAndSignature' : '.show-picture-and-signature'
		},

		events: {
			'click @ui.showPictureAndSignature' : 'fetchDeliveryDetails'
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
		},

		/**
		 * gets the photo / signature
		 */
		fetchDeliveryDetails: function() {
			// i think we should be able to use getAsync here, but idk how.. so hacky i never want to look at this again
			var details = DeliveryDetails.findOrCreate({ id: this.model.id });
			if (details.isNew()) {
				details.set('order', this.model);
				details.fetch().done(function(a, b, c) {
					debugger;
				});
			}
		}
	});

	return OrderDetailView;
});
