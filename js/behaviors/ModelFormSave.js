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
		 * @param callback
		 */
		onSetModelSaveCallbacks: function(successCallback, failCallback) {
			this.successCallback = successCallback.bind(this.view);
			this.failCallback = failCallback.bind(this.view);
		},

		saveClicked: function(evt) {
			evt.preventDefault();

			// attach events for validation failure if necessary
			if (this.getOption('showValidationErrorsOnForm')) {
				this.clearValidationErrors();
				if (!this.appliedListeners) {
					this.listenTo(this.view.model, "invalid", this.showValidationErrors);
					this.appliedListeners = true;
				}
			}

			this.setModelAttributes();
			// check if valid first, cause of a nasty bug in backbone where model.save doesn't return a rejected
			// promise if validate fails, therefore breaking the promise chain...
			if (this.view.model.isValid()) {
				this.view.model.save()
					.done(function(data) {
						if (this.successCallback) {
							this.successCallback(data);
						}
					}.bind(this))
					.fail(function(data) {
						if (this.failCallback) {
							this.failCallback(data)
						}
					}.bind(this));
			}
		},

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

		clearValidationErrors: function() {
			this.$el.find('small.error').remove();
			this.$el.find('label.error').removeClass('error');
		},

		setModelAttributes() {
			_.each(this.ui.inputs, function(input) {
				var key = $(input).attr('model_attribute');
				var value = input.value;
				this.view.model.set(key, value);
			}, this)
		}
	});

	return ModelFormSave;
});
