define([
	'marionette',
	'../util/Router',
	'tpl!templates/mvp-home.html'
], function (Mn,
			 Router,
			 tpl) {
	var MVPHomeView = Mn.ItemView.extend({
		template: tpl,

		events: {
			'click @ui.sendEmail' : 'sendEmail',
			'click @ui.closeAlert' : 'closeAlert',
			'click @ui.submitAddress' : 'addressSubmitted'
		},

		ui: {
			'streetAddress' : '.street-address',
			'zip' : '.zipcode',
			'submitAddress' : '.submit-address',
			'sendEmail': '.send-email',
			'successAlert': '.success', // sucess message
			'errorAlert': '.error', // error message
			'closeAlert': '.close', // close button in success message
			'emailAddress': '.email-address',
			'message': '.email-message'
		},

		initialize: function (options) {
		},

		onShow: function () {

		},

		addressSubmitted: function(e) {
			e.preventDefault();
			var view = this;

			if(view.validateAddress()) {
				view.showUserHome();
			} else {
				console.log('invalid');
			}
		},

		/**
		 * redirects page to show the user home (products)
		 */
		showUserHome: function() {
			Router.navigate('#home', {trigger: true});
		},

		/**
		 * This should submit a post request to check if address is within delivery area
		 * @returns {boolean}
		 */
		validateAddress: function() {
			var view = this;

			if (!view.ui.streetAddress.val() || !view.ui.zip.val()) {
				return false;
			}

			return true;
		},

		sendEmail: function (e) {
			e.preventDefault();
			var view = this;

			var fromAddress = view.ui.emailAddress.val();
			var message = view.ui.message.val();

			if (this.validateEmail()) {
				console.log('send');
				$.ajax({
					url: 'php/api/email/send',
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
