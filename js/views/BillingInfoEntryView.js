define([
	'marionette',
	'tpl!templates/billing-info-entry.html'
], function (
	Mn,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,
		tagName: 'form',
		className: 'payment-form',

		events: {
			'submit' : 'getStripeToken'
		},

		initialize: function (options) {
		},

		/**
		 * Stops the form from being submitted and sends the required details to stripe to authorize a token
		 * disables the submit button
		 * Calls stripeResponseHandler
		 * @param evt
		 */
		getStripeToken: function(evt) {
			evt.preventDefault();
			var $form = this.$el;

			// Disable the submit button to prevent repeated clicks
			var $button = $form.find('button');
			$button.prop('disabled', true);

			Stripe.card.createToken($form.context, this.stripeResponseHandler.bind(this));

			// Prevent the form from submitting with the default action
			return false;
		},

		/**
		 * handler for getting the stripe token
		 * @param status
		 * @param response
		 */
		stripeResponseHandler: function(status, response) {
			var $form = this.$el;

			if (response.error) {
				// Show the errors on the form
				$form.find('.payment-errors').text(response.error.message);
				$form.find('button').prop('disabled', false);
			} else {
				// response contains id and card, which contains additional card details
				var token = response.id;
			}
		}
	});

	return view;
});
