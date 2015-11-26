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
			'click @ui.sendEmail' : 'sendEmail',
			'click @ui.closeAlert' : 'closeAlert',
			'click @ui.submitAddress' : 'addressSubmitted',
			'click @ui.skipEntry' : 'showUserHome'
		},

		ui: {
			'streetAddress' : '.street-address',
			'submitAddress' : '.submit-address',
			'skipEntry' : '.skip-entry',
			'sendEmail': '.send-email',
			'successAlert': '.success', // success message
			'errorAlert': '.error', // error message
			'closeAlert': '.close', // close button in success message
			'emailAddress': '.email-address',
			'message': '.email-message'
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

			this.ui.streetAddress.val(this.street + ', ' + this.city + ', ' + this.state + ', ' + this.zip);
		},

		addressSubmitted: function(e) {
			e.preventDefault();
			// ensure address was entered
			if (this.validateAddress()) {
				this.updateUserAddress();
				// check if they are in a location we can deliver to
				if (this.checkAddressLocation()) {
					this.showUserHome();
				}
				else {
					console.log('bad location');
				}
			} else {
				console.log('invalid');
			}
		},

		/**
		 * Parses the autocomplete info and stores it in a UserAddressModel as well as persists
		 * in local storage for the next time the user visits. Will need to be modified soon!
		 *
		 * Will *not* support a user having multiple addresses
		 *
		 * HAS NOT BEEN TESTED to see what happens if autocomplete is not used
		 */
		updateUserAddress: function() {
			var address = new UserAddress();

			var place = this.autocomplete.getPlace() || localStorage.getItem('place');
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
			address.set('zip', zip);
			address.set('primary', 1); // make this primary address
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
			$.ajax({
				url: '/api/address/validate',
				type: 'POST',
				dataType: 'json',
				data: {
					address: this.user.get('addresses').toJSON()
				}
			}).done(function (result) {
				debugger;
				if (result.canDeliver) {
					console.log('can deliver');
				} else {
					console.log('cant deliver');
				}
			}).fail(function (result) {
			});
		},

		sendEmail: function (e) {
			e.preventDefault();
			var view = this;

			var fromAddress = view.ui.emailAddress.val();
			var message = view.ui.message.val();

			if (this.validateEmail()) {
				console.log('send');
				$.ajax({
					url: '/api/email/send',
					type: 'POST',
					dataType: 'json',
					data: {
						from: fromAddress,
						message: message
					}
				}).done(function (result) {
					view.ui.address.val('');
					view.ui.message.val('');
					view.ui.successAlert.fadeIn();
				}).fail(function (result) {
					view.ui.errorAlert.fadeIn();
				});
			}
		},

		validateEmail: function () {
			this.clearErrors();

			var addressRegex = new RegExp("^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$"); // good enough
			var address = this.ui.emailAddress.val();
			var message = this.ui.message.val();

			if (addressRegex.test(address)) {
				if (message && message.length > 1) {
					return true;
				} else {
					// add error message
					$('<small class="error">Please enter a message</small>').insertAfter(this.ui.message);
				}
			} else {
				// add error message
				$('<small class="error">Please enter a valid email</small>').insertAfter(this.ui.address);
			}

			return false;
		},

		// remove previous errors, if any
		clearErrors: function () {
			$('.error').remove();
		},

		closeAlert: function (evt) {
			evt.preventDefault();
			this.ui.successAlert.fadeOut();
		}
	});

	return MVPHomeView;
});
