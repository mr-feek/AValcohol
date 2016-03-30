/**
 * Created by Feek on 1/5/16.
 */
define([
	'marionette'
], function (
	Mn
) {
	var ModelFormSave = Mn.Behavior.extend({
		successCallback: null,
		failCallback: null,
		appliedListeners: false, // flag for if we are already listening to model validation

		ui: {
			inputs : 'input[model_attribute]',
			saveModel : '.save'
		},

		events: {
			'click @ui.saveModel' : 'saveClicked'
		},

		defaults: {
			showValidationErrorsOnForm: true
		},

		/**
		 * This behavior listens for a click on .save and cycles through each of the inputs within the views $el that
		 * has a "model_attribute" attribute and calls this.view.set(model_attribute, input.value)
		 *
		 * VIEW REQUIRES:
		 * 	- template with inputs containing [model_attribute] attribute key. IE <input model_attribute="first_name">
		 *	- optional success / fail callbacks for calling once model is saved
		 * 	- optional option showValidationErrorsOnForm
		 * @param options
		 */
		initialize: function(options) {	},

		/**
		 * Called from the view in order to set the callbacks for when model.save finishes.
		 * Functions are called with the context of the view
		 * @param successCallback
		 * @param failCallback (optional)
		 */
		onSetModelSaveCallbacks: function(successCallback, failCallback) {
			this.successCallback = successCallback.bind(this.view);

			if (failCallback) {
				this.failCallback = failCallback.bind(this.view);
			}
		},

		saveClicked: function(evt) {
			evt.preventDefault();
			debugger;

			// attach events for validation failure if necessary
			if (this.getOption('showValidationErrorsOnForm')) {
				this.clearValidationErrors();
				if (!this.appliedListeners) {
					this.listenTo(this.view.model, "invalid", this.showValidationErrors);
					this.appliedListeners = true;
				}
			}

			// get attributes to update
			var changedAttributes = this.getModelChangedAttributes();

			// wait to set until back end has saved
			var promise = this.view.model.save(changedAttributes, {	wait: true	});

			// promise will be false if validation failed and model was not set.
			// This will be picked up by our model invalid listener
			if (promise) {
				promise
					.done(function(response) {
						if (this.successCallback) {
							this.successCallback(response);
						}
					}.bind(this))
					.fail(function(response) {
						this.modelSaveFail(response);
					}.bind(this));
			}
		},

		/**
		 * This is called if the model save failed, most likely due to back end validation failure
		 * @param data
		 */
		modelSaveFail: function(response) {
			if (this.getOption('showValidationErrorsOnForm')) {
				// cycle through response and build an errors array in the expected format
				var errors = [];
				var keys = Object.keys(response.responseJSON);

				_.each(keys, function (key) {
					var attribute = key;
					var message = response.responseJSON[attribute][0];
					errors.push({
						attribute: attribute,
						message: message
					})
				});

				// show the errors returned from the api
				this.showValidationErrors(this.model, errors);
			}

			// call view supplied callback
			if (this.failCallback) {
				this.failCallback(response)
			}
		},

		/**
		 * Shows the validation errors on the form
		 * @param model
		 * @param errors
		 */
		showValidationErrors: function(model, errors) {
			_.each(errors, function(error) {
				var $input = this.$el.find('[model_attribute="' + error.attribute + '"]'); // could change this find to search directly from ui inputs.. too tired to figure that out now

				// add error class to label
				$input.parent('label').addClass('error');

				var html = '<small class="error"\>' + error.message + '</small>';
				$input.after(html);
			}.bind(this));
		},

		// for calling from view
		onShowValidationErrors: function(model, errors) {
			this.showValidationErrors(model, errors);
		},

		/**
		 * Removes all errors from the form
		 */
		clearValidationErrors: function() {
			this.$el.find('small.error').remove();
			this.$el.find('label.error').removeClass('error');
		},

		/**
		 * Retrieves all of the attributes / values to save to backend
		 * @returns {Array}
		 */
		getModelChangedAttributes() {
			var attributes = [];
			_.each(this.ui.inputs, function(input) {
				var key = $(input).attr('model_attribute');
				var value = input.value;
				attributes[key] = value;
			}, this)

			return attributes;
		}
	});

	return ModelFormSave;
});
