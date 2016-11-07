define([
	'marionette',
	'App',
	'util/Stripe',
	'shared/js/models/Card',
	'behaviors/ModelSaveAnimation',
	'templates/checkout/billing-info-entry.html'
], function (
	Mn,
	App,
	Stripe,
	Card,
	ModelSaveAnimation,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,
		tagName: 'form',
		className: 'payment-form',
		parent: null,

		behaviors: {
			ModelSaveAnimation: {
				behaviorClass: ModelSaveAnimation
			}
		},

		ui: {
			saveButton : '.save',
			back : '.back'
		},

		events: {
			'click @ui.saveButton' : 'getStripeToken',
			'click @ui.back' : 'goBackOneViewInFlow'
		},

		initialize: function (options) {
			this.parent = options.parent;

			// create card relation
			var card = Card.findOrCreate({});
			App.user.set('card', card);
			this.model = App.user.get('card');
		},

		disableSaveButton() {
			this.ui.saveButton.addClass('disabled');
		},

		enableSaveButton() {
			this.ui.saveButton.removeClass('disabled');
		},

		getStripeToken: function(evt) {
			evt.preventDefault();

			// fake a request so that our behavior will show a loading view
			this.model.trigger('request');

			// prevent repeated clicks
			if (this.ui.saveButton.hasClass('disabled')) {
				return;
			}

			var $form = this.$el;

			// Disable the submit button to prevent repeated clicks
			this.disableSaveButton();
			Stripe.card.createToken($form.context, this.stripeResponseHandler.bind(this));
		},

		/**
		 * handler for getting the stripe token
		 * @param status
		 * @param response
		 */
		stripeResponseHandler: function(status, response) {
			var $form = this.$el;

			// fake a request so that our behavior will hdie the loading view
			this.model.trigger('sync');

			if (response.error) {
				// Show the errors on the form
				var $div = $form.find('.payment-errors');
				$div.addClass('alert-box round');
				$div.text(response.error.message);
				this.enableSaveButton();
			} else {
				// response contains id and card, which contains additional card details
				var token = response.id;
				this.model.set('token', token);
				this.model.set('last_four', response.card.last4);
				this.parent.trigger('show:next');
			}
		},

		goBackOneViewInFlow: function() {
			this.parent.goToViewBasedOnName('address');
		}
	});

	return view;
});
