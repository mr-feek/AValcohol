/**
 * Created by Feek on 6/4/16.
 */
define([
	'marionette',
	'views/ProductView',
	'behaviors/Modal',
	'shared/js/models/OrderDeliveryDetails',
	'templates/order-detail.html'
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
			'showPictureAndSignature' 	: '.show-picture-and-signature',
		},

		events: {
			'click @ui.showPictureAndSignature' : 'fetchDeliveryDetails'
		},

		templateHelpers: function() {
			var user = this.model.get('user');
			var address = this.model.get('address');
			var deliveryDetails = this.model.get('delivery_details');

			return {
				userName: user.get('profile').getFullName(),
				dob: user.get('profile').getDateOfBirth(),
				orderNumber: this.model.get('id'),
				timePlaced: this.model.get('created_at'),
				vendorOrderTotal: this.model.get('vendor_order_total'),
				status: this.model.get('status').get('vendor_status'),
				address: address.getDisplayableAddress(),
				photoData: deliveryDetails ? deliveryDetails.get('photo_data') : '',
				signatureData: deliveryDetails ? deliveryDetails.get('signature') : ''
			}
		},

		initialize: function (options) {
			this.collection = this.model.get('products');
		},

		/**
		 * gets the photo / signature and rerenders
		 */
		fetchDeliveryDetails: function() {
			// i think we should be able to use getAsync here, but idk how..
			// so hacky i never want to look at this again. probably gonna cause trouble down the line oops
			if (this.model.get('delivery_details')) {
				return;
			}

			var details = DeliveryDetails.findOrCreate({ id: this.model.id });
			this.model.set('delivery_details', details);

			details.fetch().done(function(response) {
				this.render();
			}.bind(this));
		}
	});

	return OrderDetailView;
});
