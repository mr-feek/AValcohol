define([
	'marionette',
	'App',
	'models/UserAddress',
	'tpl!templates/mvp-home.html',
	'async!https://maps.googleapis.com/maps/api/js?libraries=places'
], function (
	Mn,
	app,
	UserAddress,
	tpl
) {
	var MVPHomeView = Mn.ItemView.extend({
		template: tpl,

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

		initialize: function (options) {
			this.router = app.router;
			this.user = app.user;
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
			// ensure address was entered
			if (this.validateAddress()) {
				this.updateUserAddress();
				// check if they are in a location we can deliver to
				this.checkAddressLocation();
			} else {
				// they didn't enter anything into the box
				this.showError('Please enter a valid address.');
			}
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
			var address = UserAddress.findOrCreate({});

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

			address.set('street', street);
			address.set('city', city);
			address.set('state', state);
			address.set('zipcode', zip);
			//address.set('unit', unit);

			// this may cause a problem down the line
			app.user.set('addresses',  address);
		},

		/**
		 * redirects page to show the user home (products)
		 */
		showUserHome: function() {
			this.router.navigate('#home', {trigger: true});
		},

		/**
		 * This should submit a post request to check if address is within delivery area
		 * @returns {boolean}
		 */
		validateAddress: function() {
			if (!this.ui.streetAddress.val()) {
				return false;
			}

			return true;
		},

		/**
		 * check if we can deliver to this person
		 */
		checkAddressLocation: function() {
			var view = this;

			$.ajax({
				url: '/api/address/validate',
				type: 'POST',
				dataType: 'json',
				data: {
					address: this.user.get('addresses').at(0).toJSON() // TO DO: get the right address, not just the first
				}
			}).done(function (result) {
				if (result.canDeliver) {
					view.showUserHome();
				} else {
					view.showError('Sorry, we are currently only delivering to State College, PA 16801.');
				}
			}).fail(function (result) {
				view.showError('Sorry, something went wrong on our end. Our support team has been notified of the issue.');
			});
		},

		/**
		 * Displays an error alert banner at the top of the page with the specified message.
		 * Does not support multiple alerts at one time currently.
		 * @param message
		 */
		showError: function(message) {
			var view = this;

			this.ui.alertArea.html('<div data-alert class="alert-box alert round text-center"> \
				<div class="message">' + message + '</div> \
				<a href="#" class="close">&times;</a> \
			</div>');

			// fade out the error after 7 seconds
			setTimeout(function () {
				view.hideError();
			}, 7000);
		},

		/**
		 * Clears the error message and removes it from the screen
		 */
		hideError: function() {
			this.ui.alertArea.find('.alert-box').fadeOut();
		}
	});

	return MVPHomeView;
});
