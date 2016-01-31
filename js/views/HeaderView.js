define([
	'marionette',
	'util/Vent',
	'tpl!templates/header.html'
], function(
	Mn,
	Vent,
	tpl
) {
	var HeaderView = Mn.ItemView.extend({
		template: tpl,

		events: {
			'click @ui.link' : 'linkClicked',
			'click @ui.join' : 'joinClicked'
		},

		ui: {
			link : '.top-bar-section .right *', // either li or anchor
			secondaryHeader : '.secondary-header',
			email : '.email',
			join : '.join'
		},

		initialize: function(options) {
			_.bindAll(this, 'joinClicked', 'emailSubmitSuccess');
		},

		onShow: function() {
			setTimeout(function() {
				this.ui.secondaryHeader.slideDown();
			}.bind(this), 1000);
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
				this.ui.secondaryHeader.html('<p class="text-center">You have been registered for our soft launch on March 1st!</p>');
				setTimeout(function() {
					this.ui.secondaryHeader.slideUp();
				}.bind(this), 4000);
			}
			else {
				console.error('something went wrong');
			}
		},

		linkClicked: function(evt) {
			var selector = '';
			switch(evt.target.className) {
				case 'customer':
					selector = '.customers';
					break;
					/*
				case 'restaurant':
					selector = '.restaurants';
					break;
					*/
				case 'retailer':
					selector = '.retailers';
					break;
				case 'button round contact':
					selector = ".contact-us";
					break;
			}

			Vent.trigger('root:scrollTo', selector);
		}
	});

	return HeaderView;
});
