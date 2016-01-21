/**
 * Created by Feek on 1/21/16.
 */
define([
	'marionette',
	'App',
	'models/Product',
	'models/User',
	'models/UserAddress',
	'models/Order',
	'models/Card',
	'tpl!templates/checkout/order-review.html'
], function (
	Mn,
	App,
	Product,
	User,
	UserAddress,
	Order,
	Card,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,
		tagName: 'div',
		className: '',
		parent: null,

		templateHelpers: function() {
			return {
				user: App.user,
				address: App.user.get('address'),
				card: App.user.get('card')
			}
		},

		events: {
			'click @ui.edit' : 'goToView',
			'click @ui.submitOrder' : 'submitOrder'
		},

		ui: {
			edit : '.edit',
			submitOrder : '.button.order',
			note : '.note'
		},

		initialize: function (options) {
			this.parent = options.parent;

			this.listenTo(App.user, 'sync', this.modelChanged);
			this.listenTo(App.user.get('address'), 'sync', this.modelChanged);
			this.listenTo(App.user.get('card'), 'sync', this.modelChanged);
		},

		modelChanged: function() {
			console.log('re rendering');
			this.render();
		},

		/**
		 * This function parses the class name of the clicked div and figures out which
		 * view to show. Must be kept in sync with checkout views flow
		 * @param evt
		 */
		goToView: function(evt) {
			var index;
			var target = evt.target.className.replace('edit ', '');

			switch(target) {
				case 'user':
					index = 0;
					break;
				case  'address':
					index = 1;
					break;
				case 'billing':
					index = 2;
					break;
			}

			this.parent.goToIndex(index);
		},

		/**
		 * creates a new order model and saves it to the backend
		 * @param token verified stripe token
		 */
		submitOrder: function(token) {
			// get note
			var note = this.ui.note.val();

			// create order
			var order = Order.findOrCreate({
				products: App.cart,
				user: App.user,
				address: App.user.get('address'),
				stripe_token: App.user.get('card').get('token'),
				note: note
			});

			order.save().done(function (result) {
				console.log('pass');
			}).fail(function (result) {
				console.log('fail');
			});
		}
	});

	return view;
});
