/**
 * Created by Feek on 1/5/16.
 */
define([
	'marionette'
], function (
	Mn
) {
	var ModelValidation = Mn.Behavior.extend({

		/**
		 * This behavior cycles through a views modelsToValidate array of models and listens
		 * for an invalid event. When this happens, the behavior displays a list of error messages
		 * at the top of the view in an alert bar
		 *
		 * VIEW REQUIRES:
		 * - ui.alertArea (defaults to top of view)
		 * - modelsToValidate (defaults to views model)
		 *
		 * @param options
		 */
		initialize: function(options) {
		},

		onShow: function() {
			if (!this.view.modelsToValidate) {
				this.view.modelsToValidate = [this.view.model];
			}

			// models to validate should have been set in initialize of the view, so were good to set up listeners
			_.each(this.view.modelsToValidate, function(model) {
				this.listenTo(model, "invalid", this.validationError);
			}, this);
		},

		validationError: function(model, message) {
			this.showError(message);
		},

		showError: function(message) {
			if (!this.view.ui.alertArea) {
				// insert alert area into the views $el and cache it into views ui as alertArea
				var html = '<div class="alert-area"></div>'
				this.view.$el.prepend(html);
				this.view.ui.alertArea = this.view.$el.find('.alert-area');
			}

			this.view.ui.alertArea.append('<div data-alert class="alert-box alert round text-center"> \
				<div class="message">' + message + '</div> \
				<a href="#" class="close">&times;</a> \
			</div>');

			// reflow foundation so that we can listen to alert close events
			$(this.view.$el).foundation('alert', 'reflow');
		}
	});

	return ModelValidation;
});
