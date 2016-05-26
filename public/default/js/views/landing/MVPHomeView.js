define([
	'marionette',
	'App',
	'views/landing/CannotDeliverView',
	'shared/js/models/UserAddress',
	'behaviors/ModelValidation',
	'tpl!templates/landing/mvp-home.html',
	'async!https://maps.googleapis.com/maps/api/js?libraries=places'
], function (
	Mn,
	app,
	CannotDeliverView,
	UserAddress,
	ModelValidation,
	tpl
) {
	var MVPHomeView = Mn.ItemView.extend({
		template: tpl,
		modelsToValidate: [],

		events: {
			'click @ui.submitAddress' : 'addressSubmitted',
			'keydown @ui.streetAddress' : 'addressSubmitted'
		},

		ui: {
			'streetAddress' : '.street-address',
			'submitAddress' : '.submit-address',
			'alertArea' : '.alert-area'
		},

		behaviors: {
			ModelValidation: {
				behaviorClass: ModelValidation
			}
		},

		initialize: function () {
			this.router = app.router;
			this.user = app.user;
			this.address = this.user.get('address');
			this.modelsToValidate.push(this.user, this.address); // dont think we need to listen to user, but why not..
		},

		onShow: function () {
			var input = this.ui.streetAddress[0];
			var options = {
				types: ['address'], // only precise locations, no businesses or landmarks
			};
			this.autocomplete = new google.maps.places.Autocomplete(input, options);
		},

		/**
		 * enter or click button will continue
		 * @param e
		 */
		addressSubmitted: function(e) {
			if (e.keyCode && e.keyCode !== 13) {
				return;
			}
			e.preventDefault();
			this.updateUserAddress();
		},

		/**
		 * Parses the autocomplete info and stores it in a UserAddressModel
		 */
		updateUserAddress: function() {
			var place = this.autocomplete.getPlace();
			var state, zip;

			// bruteforce to find which element of the array is tha state / zip
			// this could be more efficient
			_.each(place.address_components, function(component) {
				_.each(component.types, function(type) {
					if (type === 'administrative_area_level_1') {
						state = component.short_name;
					}
					else if(type === 'postal_code') {
						zip = component.short_name;
					}
				});
			});

			this.address.set({
				'street' : place.name,
				'city' : place.vicinity,
				'state' : state,
				'zipcode' : zip,
				'location' : {
					'longitude' : place.geometry.location.lng(),
					'latitude' : place.geometry.location.lat()
				}
			});

			if (this.address.isValid()) {
				this.address.getDeliveryZone().done(function(resp) {
					if (resp.success) {
						this.showUserHome();
					} else {
						this.showCannotDeliverView();
					}
				}.bind(this));
			}
		},

		/**
		 * redirects page to show the user home (products)
		 */
		showUserHome: function() {
			this.router.navigate('#home', {trigger: true});
		},

		showCannotDeliverView: function() {
			app.rootView.getRegion('modalRegion').show(new CannotDeliverView());
		}
	});

	return MVPHomeView;
});
