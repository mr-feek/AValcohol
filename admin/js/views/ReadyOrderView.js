/**
 * Created by Feek on 3/22/16.
 */
define([
	'marionette',
	'views/ProductView',
	'tpl!templates/ready-order.html'
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
				vendorOrderTotal: this.model.get('vendor_order_total'),
				status: this.model.get('status').get('vendor_status')
			}
		},

		events: {
			'click @ui.accept' : 'acceptOrder'
		},

		ui: {
			accept: '.accept'
		},

		initialize: function (options) {
			this.collection = this.model.get('products');
		},

		acceptOrder: function(e) {
			e.preventDefault();

			this.model.get('status').set('delivery_status', 'out-for-delivery').save().done(function() {
				this.$el.slideUp(400, function() {
					this.collection.remove(this.model);
					this.destroy();
				}.bind(this));
			}.bind(this));
		}
	});

	return view;
});
