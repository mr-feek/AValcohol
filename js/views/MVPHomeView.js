define([
	'marionette',
	'App',
	'models/UserAddress',
	'behaviors/ModelValidation',
	'tpl!templates/mvp-home.html',
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
			this.address = UserAddress.findOrCreate({});
			this.modelsToValidate.push(this.user, this.address); // dont think we need to listen to user, but why not..
		},

		onShow: function () {
			var input = this.ui.streetAddress[0];
			var options = {
				types: ['address'], // only precise locations, no businesses or landmarks
			};
			this.autocomplete = new google.maps.places.Autocomplete(input, options);

			// load in last used address
			this.place_id = localStorage.getItem('place_id');
			this.street = localStorage.getItem('street');
			this.city = localStorage.getItem('city');
			this.state = localStorage.getItem('state');
			this.zip = localStorage.getItem('zip');
			//this.unit = localStorage.getItem('unit');

			// if there was a saved address, populate it into the textfield
			if (this.street && this.city && this.state && this.zip) {
				this.ui.streetAddress.val(this.street + ', ' + this.city + ', ' + this.state + ', ' + this.zip);
			}

		},

		addressSubmitted: function(e) {
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

			if (!place) {
				// this means that the place was cached via local storage (no keystrokes in the textbox)
				var street = this.street;
				var city = this.city;
				var state = this.state;
				var zip = this.zip;
			} else {
				var place_id = place.place_id;
				var street = place.name;
				var city = place.vicinity;
				var state;
				var zip;

				// bruteforce to find which element of the array is tha state / zip
				// this could be more efficient
				_.each(place.address_components, function(component) {
					_.each(component.types, function(type) {
						if (type == 'administrative_area_level_1') {
							state = component.short_name;
						}
						else if(type == 'postal_code') {
							zip = component.short_name;
						}
					});
				});
			}

			localStorage.setItem('place', place);
			localStorage.setItem('place_id', place_id);
			localStorage.setItem('street', street);
			localStorage.setItem('city', city);
			localStorage.setItem('state', state);
			localStorage.setItem('zip', zip);
			//localStorage.setItem('unit', unit);

			this.address.set('street', street);
			this.address.set('city', city);
			this.address.set('state', state);
			this.address.set('zipcode', zip);
			//address.set('unit', unit);

			if (this.address.isValid()) {
				// set up the relation
				this.user.set('address',  this.address, {validate: true});
				this.showUserHome();
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
