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
				userName: user.get('profile').getFullName(),
				dob: user.get('profile').getDateOfBirth(),
				orderNumber: this.model.get('id'),
				timePlaced: this.model.get('created_at'),
				vendorOrderTotal: this.model.calculateVendorOrderTotal,
				status: this.model.get('status').get('vendor_status')
			}
		},

		events: {
			'click @ui.reject' : 'rejectOrder',
			'click @ui.accept' : 'acceptOrder'
		},

		ui: {
			reject: '.reject',
			accept: '.accept',
			footer: '.footer'
		},

		initialize: function (options) {
			this.collection = this.model.get('products');
			_.bindAll(this, 'modelAcceptedSuccess', 'modelRejectedSuccess', 'slideOutView');
		},

		rejectOrder: function(e) {
			e.preventDefault();
			this.model.get('status').save({
				vendor_status : 'rejected'
			}, {
				patch: true,
				success: this.modelRejectedSuccess,
				error: function(model, response, options) {
					alert('something went wrong rejecting this order. Please let AValcohol know ASAP');
				}
			});
		},

		acceptOrder: function(e) {
			e.preventDefault();
			this.model.get('status').save({
				vendor_status : 'accepted'
			}, {
				patch: true,
				success: this.modelAcceptedSuccess,
				error: function(model, response, options) {
					alert('something went wrong rejecting this order. Please let AValcohol know ASAP');
				}
			});
		},

		modelAcceptedSuccess: function() {
			this.ui.footer.hide();
			this.$el.find(this.childViewContainer).html('<h3 class="text-center">Order Accepted</h3>');
			setTimeout(this.slideOutView, 2000);
		},

		slideOutView: function() {
			this.$el.slideUp(400, function() {
				this.destroy();
			}.bind(this));
		},

		/**
		 * essentially replace the html to say order rejected and then delete this view
		 * @param model
		 * @param response
		 * @param options
		 */
		modelRejectedSuccess: function(model, response, options) {
			this.ui.footer.hide();
			this.$el.find(this.childViewContainer).html('<h3 class="text-center">Order Rejected</h3>');
			setTimeout(this.slideOutView, 2000);
		}
	});

	return view;
});
