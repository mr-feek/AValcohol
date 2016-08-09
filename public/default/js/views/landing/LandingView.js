define([
	'marionette',
	'App',
	'views/landing/CannotDeliverDueToBlacklistedAddressView',
	'views/landing/CannotDeliverView',
	'shared/js/models/UserAddress',
	'behaviors/ModelValidation',
	'behaviors/LoadingIndicator',
	'tpl!templates/landing/landing.html',
	'async!https://maps.googleapis.com/maps/api/js?key=AIzaSyBcB7pNSUkuNH6awEIE57njndRXQpQBsEI&libraries=places'
], function (
	Mn,
	app,
	CannotDeliverDueToBlacklistedAddressView,
	CannotDeliverView,
	UserAddress,
	ModelValidation,
	CollectionLoading,
	tpl
) {
	var LandingView = Mn.ItemView.extend({
		template: tpl,
		modelsToValidate: [],
		className: 'class row expanded collapse',

		events: {
			'click @ui.submitAddress' 	: 'addressSubmitted',
			'keydown @ui.streetAddress' : 'addressSubmitted'
		},

		ui: {
			'logo'			: '.logo',
			'tagline'		: '.info',
			'streetAddress' : '.street-address',
			'apartmentNumber': '.apartment',
			'submitAddress' : '.submit-address',
			'alertArea' 	: '.alert-area'
		},

		behaviors: {
			ModelValidation: {
				behaviorClass: ModelValidation
			},
			CollectionLoading: {
				behaviorClass: CollectionLoading
			}
		},

		initialize: function () {
			this.router = app.router;
			this.user = app.user;
			this.address = this.user.get('address');
			this.triggerMethod('setListener', this.address);
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
			
			if (!place) {
				return;
			}

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

			var aptNumber = this.ui.apartmentNumber.val();

			this.address.set({
				'street' : place.name,
				'city' : place.vicinity,
				'state' : state,
				'zipcode' : zip,
				'location' : {
					'longitude' : place.geometry.location.lng(),
					'latitude' : place.geometry.location.lat()
				},
				'apartment_number' : aptNumber
			});
			
			if (this.address.isValid()) {
				this.address.getDeliveryZone().done(function(resp) {
					if (resp.success === true) {
						this.showUserHome();
						return;
					}

					this.showCannotDeliverView(resp);
				}.bind(this));
			}
		},

		/**
		 * redirects page to show the user home (products)
		 */
		showUserHome: function() {
			this.router.navigate('#home', {trigger: true});
		},

		showCannotDeliverView: function(response) {
			if (response.reason == 'blacklisted') {
				app.rootView.getRegion('modalRegion').show(new CannotDeliverDueToBlacklistedAddressView({ message: response.message }));
				return;
			}
			
			app.rootView.getRegion('modalRegion').show(new CannotDeliverView());
		}
	});

	return LandingView;
});
