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
			var address = this.model.get('address');

			return {
				userName: user.get('profile').getFullName(),
				dob: user.get('profile').getDateOfBirth(),
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

			this.model.get('status').save({
				'delivery_status': 'out-for-delivery'
			}, {
				patch: true
			}).done(function() {
				this.$el.slideUp(400, function() {
					this.collection.remove(this.model);
					this.destroy();
				}.bind(this));
			}.bind(this));
		}
	});

	return view;
});
