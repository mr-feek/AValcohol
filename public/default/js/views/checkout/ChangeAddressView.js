/**
 * Created by Feek on 8/10/16.
 */
define([
	'marionette',
	'App',
	'shared/js/models/UserAddress',
	'behaviors/Modal',
	'tpl!templates/checkout/change-address.html',
	'async!https://maps.googleapis.com/maps/api/js?key=AIzaSyBcB7pNSUkuNH6awEIE57njndRXQpQBsEI&libraries=places'
], function (
	Mn,
	app,
	UserAddress,
	Modal,
	tpl
) {
	var ChangeAddressView = Mn.ItemView.extend({
		template: tpl,
		temporaryModel: null,

		templateHelpers: function () {
			return {}
		},

		behaviors: {
			Modal: {
				behaviorClass: Modal
			},
		},

		events: {
			'click @ui.save' : 'saveClicked'
		},

		ui: {
			'alertArea'			: '.alert-area',
			'autocomplete' 		: '.street-address',
			'apartmentNumber' 	: '.apartment',
			'save' 				: '.save'
		},

		initialize: function( options) {
		},
		
		onShow: function() {
			var input = this.ui.autocomplete[0];
			var options = {
				types: ['address'], // only precise locations, no businesses or landmarks
			};
			this.autocomplete = new google.maps.places.Autocomplete(input, options);

			google.maps.event.addListener(this.autocomplete, 'place_changed', this.addressChanged.bind(this));
		},

		addressChanged: function() {
			this.createTemporaryAddressModelAndFetchDeliveryZone();
		},

		enableSaveButton: function() {
			this.ui.save.removeClass('disabled');
		},

		showSuccessMessage: function() {
			this.ui.alertArea.html('' +
				'<div class="callout small success">' +
					'Congratulations! We can successfully change your order to use your new address without a change in price. ' +
					'To proceed, please click "save" below.' +
				'</div>'
			);
		},

		// create a temporary model out of the new place and fetch the delivery zone
		// if the delivery zone is the same as the current model, good to go!
		// otherwise, we will have to display a warning to the user and
		// return them to the store to re add products to their cart.
		//
		// clear cart or try to fetch new products based on p id?
		createTemporaryAddressModelAndFetchDeliveryZone: function() {
			this.temporaryModel = new UserAddress();
			this.updateTemporaryAddressModel();

			if (this.temporaryModel.isValid()) {
				this.temporaryModel.getDeliveryZone().done(function(response) {
					if (response.success) {
						if (response.delivery_zone_id === this.model.get('delivery_zone_id')) {
							this.allowUserToChangeAddress();
						} else {
							// show warning message that they will have to return to the store to change address
						}
						
						return;
					}

					// cannot deliver at all to newly supplied address for whatever reason
					this.showCannotDeliveryView();
				}.bind(this));
			}
		},

		allowUserToChangeAddress: function() {
			this.enableSaveButton();
			this.showSuccessMessage();
		},

		saveClicked: function (evt) {
			evt.preventDefault();
			
			if (this.ui.save.hasClass('disabled')) {
				return;
			}

			// update the address model stored in session to reflect the newer temporary model we created.
			this.model.clear();
			this.model.set(this.temporaryModel.toJSON());

			// close this modal and delete this view
			this.triggerMethod('closeModal');
		},

		// this logic is taken from landing view. should probably move to model.
		updateTemporaryAddressModel() {
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

			this.temporaryModel.set({
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
		}
	});

	return ChangeAddressView;
});
