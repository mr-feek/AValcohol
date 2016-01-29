define([
	'marionette',
	'tpl!templates/home.html'
], function(
	Mn,
	tpl
) {
	var HomeView = Mn.ItemView.extend({
		template: tpl,

		events: {
			'click @ui.sendEmail' : 'sendEmail',
			'click @ui.closeAlert' : 'closeAlert'
		},

		ui: {
			'carousel' : '.carousel',
			'sendEmail' : '.send-email',
			'successAlert' : '.success', // sucess message
			'errorAlert' : '.error', // error message
			'closeAlert' : '.close', // close button in success message
			'address' : '.address', // email address
			'message' : '.message' // email message
		},

		initialize: function(options) {
		},

		onShow: function() {
			this.ui.carousel.slick({
				autoplay: true,
				arrows: false,
				pauseOnHover: false,
				autoplaySpeed: 4000
			});
		},

		sendEmail: function(e) {
			e.preventDefault();
			var view = this;

			var fromAddress = view.ui.address.val();
			var message = view.ui.message.val();

			if (this.validateEmail()) {
				console.log('send');
				$.ajax({
			        url: 'php/api/email/send',
			        type: 'POST',
			        dataType: 'json',
					data: {
						from: fromAddress,
						message : message
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

		validateEmail: function() {
			this.clearErrors();

			var addressRegex = new RegExp("^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$"); // good enough
			var address = this.ui.address.val();
			var message = this.ui.message.val();

			if (addressRegex.test(address)) {
				if (message && message.length > 1) {
					return true;
				} else {
					// add error message
					$('<small class="error">Please enter a message</small>').insertAfter(this.ui.message);
				}
			} else  {
				// add error message
				$('<small class="error">Please enter a valid email</small>').insertAfter(this.ui.address);
			}

			return false;
		},

		// remove previous errors, if any
		clearErrors: function() {
			$('.error').remove();
		},

		closeAlert: function(evt) {
			evt.preventDefault();
			this.ui.successAlert.fadeOut();
		}
	});

	return HomeView;
});
