/**
 * Created by Feek on 1/21/16.
 */
define([
	'marionette',
	'App',
	'shared/js/models/Product',
	'shared/js/models/User',
	'shared/js/models/UserAddress',
	'shared/js/models/Order',
	'shared/js/models/Card',
	'views/cart/CartProductView',
	'behaviors/ModelSaveAnimation',
	'shared/js/util/Vent',
	'tpl!templates/checkout/order-review.html'
], function (
	Mn,
	App,
	Product,
	User,
	UserAddress,
	Order,
	Card,
	CartProductView,
	ModelSaveAnimation,
	Vent,
	tpl
) {
	var view = Mn.CompositeView.extend({
		template: tpl,
		tagName: 'div',
		className: '',
		parent: null,
		childView: CartProductView,
		childViewContainer: '.products',

		templateHelpers: function() {
			return {
				user: App.user,
				address: App.user.get('address'),
				card: App.user.get('card'),
				products: App.cart,
				number: this.collection.length,
				subtotal: this.collection.calculateSubtotal
			}
		},

		behaviors: {
			ModelSaveAnimation: {
				behaviorClass: ModelSaveAnimation
			}
		},

		events: {
			'click @ui.edit' : 'goToView',
			'click @ui.submitOrder' : 'submitOrder'
		},

		ui: {
			edit 		: '.edit',
			submitOrder : '.button.order',
			note 		: '.note',
			terms 		: '#terms'
		},

		initialize: function (options) {
			this.parent = options.parent;
			this.model = Order.findOrCreate({});
			this.collection = App.cart;
		},

		onShow: function() {
			// subscribe to these events so that if a user goes back in the flow, the info
			// is updated here
			this.listenTo(App.user, 'sync', this.modelHasChanged);
			this.listenTo(App.user.get('address'), 'sync', this.modelHasChanged);
			this.listenTo(App.user.get('card'), 'sync', this.modelHasChanged);
		},

		modelHasChanged: function() {
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
		
			this.parent.goToViewBasedOnIndex(index);
		},

		/**
		 * creates a new order model and saves it to the backend
		 * @param token verified stripe token
		 */
		submitOrder: function(token) {
			var termsAccepted = this.ui.terms.is(':checked');
			if (!termsAccepted) {
				// todo: highlight as required
				alert('you must accept the terms and conditions in order to request your order.');
				return;
			}

			// get note
			var note = this.ui.note.val();

			// create order
			this.model.set({
				products: App.cart,
				user: App.user,
				address: App.user.get('address'),
				stripe_token: App.user.get('card').get('token'),
				note: note,
				terms_and_conditions: termsAccepted
			});

			this.model.save()
				.done(function (result) {
					Vent.trigger('order:submitted', this.model);
				}.bind(this))
				.fail(function (result) {
					alert("Sorry, but something went wrong with creating your order, please let us know" +
						" by clicking on the feedback button at the bottom of this page. Don't worry," +
						" we did NOT charge your credit card!");
				});
		}
	});

	return view;
});
