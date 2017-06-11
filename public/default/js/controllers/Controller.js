/**
 * Created by Feek on 10/13/15.
 */

define([
	'marionette',
	'views/HeaderView',
	'views/landing/LandingView',
	'views/user-home/UserHomeView',
	'views/checkout/CheckoutView',
	'views/misc/TermsAndConditionsView',
	'views/NotFoundView',
	'shared/js/Brain',
	'App'
], function (
	Marionette,
	HeaderView,
	LandingView,
	UserHomeView,
	CheckoutView,
	TermsAndConditionsView,
	NotFoundView,
	Brain,
	app
) {
	var Controller = Marionette.Object.extend({
		rootView: null,

		initialize: function(options) {
			this.rootView = options.rootView;

			_.bindAll(this, 'showHome', 'showCheckout');
		},

		showHome: function() {
			var region = this.rootView.getRegion('main');
			region.show(new LandingView());
		},

		showUserHome: function(endpoint) {
			if (!app.user.get('address').get('delivery_zone_id')) {
				app.router.navigate('', {trigger: true});
				return;
			}

			var region = this.rootView.getRegion('main');
			// if we already have a userhomeview rendered, just swap out the products view
			if (!region.currentView || !(region.currentView instanceof UserHomeView)) {
				region.show(new UserHomeView({ endpoint: endpoint }));
			}

			var sort = this.getQueryStringParameter('sort');

			if (sort) {
				var products = Brain.retrieve('products');
				
				switch(sort) {
					case 'price-low': {
						products.comparator = function(product) {
							return product.get('pivot').sale_price * 100;
						};
						products.sort();
						break;
					}

					case 'price-high': {
						products.comparator = function(product) {
							return product.get('pivot').sale_price * 100 * -1;
						};
						products.sort();
						break;
					}

					case 'featured': {
						products.comparator = function(product) {
							return product.get('featured');
						};
						products.sort();
						break;
					}
				}
			}
		},

		showCheckout: function() {
			if (app.cart.length == 0) {
				alert('You don\'t have any items in your cart! Let\'s change that.');
				app.router.navigate('', {trigger: true});
				return;
			}
			var region = this.rootView.getRegion('main');
			region.show(new CheckoutView({	region: region }));
		},

		showTermsAndConditions: function() {
			var region = this.rootView.getRegion('main');
			region.show(new TermsAndConditionsView());
		},

		routeNotFound: function() {
			var region = this.rootView.getRegion('main');
			region.show(new NotFoundView());
		},

		getQueryStringParameter: function (key) {
			key = key.replace(/[\[\]]/g, "\\$&");
			var url = window.location.href;

			var regex = new RegExp("[?&]" + key + "(=([^&#]*)|&|#|$)");
			var results = regex.exec(url);

			if (!results) return null;
			if (!results[2]) return '';
			return decodeURIComponent(results[2].replace(/\+/g, " "));
		}
	});

	return Controller;
});
