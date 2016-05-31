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
				dob: user.get('profile').date_of_birth,
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
			_.bindAll(this, 'modelSaveSuccess');
		},

		rejectOrder: function(e) {
			e.preventDefault();
			this.model.get('status').set('vendor_status', 'rejected');
			this.model.get('status').save([], {
				success: this.modelSaveSuccess,
				error: function(model, response, options) {
					alert('something went wrong rejecting this order. Please let AValcohol know ASAP');
				}
			});
		},

		acceptOrder: function(e) {
			e.preventDefault();
		},

		/**
		 * essentially replace the html to say order rejected and then delete this view
		 * @param model
		 * @param response
		 * @param options
		 */
		modelSaveSuccess: function(model, response, options) {
			this.ui.footer.hide();
			this.$el.find(this.childViewContainer).html('<h3 class="text-center">Order Rejected</h3>');
			setTimeout(function() {
				this.$el.slideUp(400, function() {
					this.destroy();
				}.bind(this));
			}.bind(this), 2000);
		}
	});

	return view;
});
