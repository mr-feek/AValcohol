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
			'click @ui.closeAlert' : 'closeAlert',
			'click @ui.join' : 'joinClicked'
		},

		ui: {
			'carousel' : '.carousel',
			'sendEmail' : '.send-email',
			'successAlert' : '.success', // sucess message
			'errorAlert' : '.error', // error message
			'closeAlert' : '.close', // close button in success message
			'address' : '.address', // email address
			'message' : '.message', // email message,
			secondaryHeader : '.secondary-header',
			email : '.email', // mvp join signup email
			join : '.join' // mvp email button
		},

		initialize: function(options) {
			_.bindAll(this, 'joinClicked', 'emailSubmitSuccess');
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
		},

		joinClicked: function(evt) {
			evt.preventDefault();
			var email = this.ui.email.val();
			var regex = /\S+@\S+\.\S+/;

			if (regex.test(email)) {
				this.submitEmail(email);
			} else {
				alert('Please enter a valid email address');
			}
		},

		submitEmail: function(email) {
			$.post(
				'/php/api/email/create',
				{email : email},
				this.emailSubmitSuccess
			)
		},

		emailSubmitSuccess: function(response) {
			if (response.success) {
				this.ui.secondaryHeader.prepend('' +
					'<div class="columns small-12">' +
						'<div data-alert class="alert-box success radius">Thanks for signing up!</div>' +
					'</div>');
			}
			else {
				console.error('something went wrong');
			}
		},
	});

	return HomeView;
});
