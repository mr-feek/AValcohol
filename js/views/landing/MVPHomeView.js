define([
	'marionette',
	'App',
	'../../../shared/js/models/UserAddress',
	'../../../shared/js/behaviors/ModelValidation',
	'tpl!templates/landing/mvp-home.html',
	'async!https://maps.googleapis.com/maps/api/js?libraries=places'
], function (
	Mn,
	app,
	UserAddress,
	ModelValidation,
	tpl
) {
	var MVPHomeView = Mn.ItemView.extend({
		template: tpl,
		modelsToValidate: [],

		events: {
			'click @ui.submitAddress' : 'addressSubmitted',
			'keydown @ui.streetAddress' : 'addressSubmitted',
			'click @ui.skipEntry' : 'showUserHome'
		},

		ui: {
			'streetAddress' : '.street-address',
			'submitAddress' : '.submit-address',
			'skipEntry' : '.skip-entry',
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
		 * Parses the autocomplete info and stores it in a UserAddressModel as well as persists
		 * in local storage for the next time the user visits. Will need to be modified soon!
		 *
		 * Will *not* support a user having multiple addresses
		 *
		 * HAS NOT BEEN TESTED to see what happens if autocomplete is not used. Will need to provide support for that
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

			this.address.set('street', place.name);
			this.address.set('city', place.vicinity);
			this.address.set('state', state);
			this.address.set('zipcode', zip);
			this.address.set('location', {
				'longitude': place.geometry.location.lng(),
				'latitude': place.geometry.location.lat()
			});

			if (this.address.isValid()) {
				this.address.getDeliveryZone().done(function() {
					this.showUserHome();
				}.bind(this));
			}
		},

		/**
		 * redirects page to show the user home (products)
		 */
		showUserHome: function() {
			this.router.navigate('#home', {trigger: true});
		}
	});

	return MVPHomeView;
});
