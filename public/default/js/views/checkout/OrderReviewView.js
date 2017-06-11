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
	'views/user-home/StoreClosedView',
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
	StoreClosedView,
	ModelSaveAnimation,
	Vent,
	tpl
) {
	var view = Mn.CompositeView.extend({
		template: tpl,
		tagName: 'div',
		className: 'order-review-view',
		parent: null,
		childView: CartProductView,
		childViewContainer: '.products',

		templateHelpers: function() {
			return {
				user: App.user,
				address: App.user.get('address'),
				card: App.user.get('card'),
				products: this.collection,
				number: this.collection.getNumberOfItemsInCart(),
				subtotal: this.collection.calculateSubtotal,
				total: this.collection.calculateTotal,
				deliveryFee: this.collection.calculateDeliveryFee,
				tax: this.collection.calculateTax
			}
		},

		behaviors: {
			ModelSaveAnimation: {
				behaviorClass: ModelSaveAnimation
			}
		},

		collectionEvents: {
			'update' 			: 'productsChanged',
			'change:quantity' 	: 'productsChanged'
		},

		events: {
			'click @ui.edit' : 'goToView',
			'click @ui.submitOrder' : 'submitOrder'
		},

		ui: {
			edit 		: '.edit',
			submitOrder : '.button.order',
			note 		: '.note',
			terms 		: '#terms',
			numProducts: '.num-products',
			subTotal			: '.subtotal',
			tax					: '.tax',
			deliveryFee			: '.delivery-fee',
			totalAmount			: '.total-amount'
		},

		initialize: function (options) {
			this.parent = options.parent;
			this.model = Order.findOrCreate({});
			this.collection = options.collection; // if called App.cart here, collectionEvents not listened to
		},

		productsChanged: function() {
			this.updateTotals();
		},

		updateTotals: function() {
			var subtotal = App.cart.calculateSubtotal();
			var numberOfItems = App.cart.getNumberOfItemsInCart();
			var tax = App.cart.calculateTax();
			var deliveryFee = App.cart.calculateDeliveryFee();
			var total = App.cart.calculateTotal();

			this.ui.subTotal.html('$' + subtotal);
			this.ui.numProducts.html(numberOfItems + 'Items');
			this.ui.tax.html('$' + tax);
			this.ui.deliveryFee.html('$' + deliveryFee);
			this.ui.totalAmount.html('$' + total);
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
			var target = evt.currentTarget.className.replace('edit ', '');

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
					if (result.success !== false) {
						Vent.trigger('order:submitted', this.model);
					} else {
						if (result.isClosed === true) {
							App.config.set('isClosed', true);
							App.config.set('closedMessage', result.message);

							App.rootView.getRegion('modalRegion').show(new StoreClosedView());
						}
					}
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
