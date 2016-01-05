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
		 * - ui.alertArea
		 * - modelsToValidate
		 *
		 * @param options
		 */
		initialize: function(options) {
		},

		onShow: function() {
			// models to validate should have been set in initialize of the view, so were good to set up listeners
			_.each(this.view.modelsToValidate, function(model) {
				this.listenTo(model, "invalid", this.validationError);
			}, this);
		},

		validationError: function(model, message) {
			this.showError(message);
		},

		showError: function(message) {
			this.view.ui.alertArea.append('<div data-alert class="alert-box alert round text-center"> \
				<div class="message">' + message + '</div> \
				<a href="#" class="close">&times;</a> \
			</div>');
		}
	});

	return ModelValidation;
});
