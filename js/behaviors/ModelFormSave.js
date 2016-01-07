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

		ui: {
			inputs : 'input[model_attribute]',
			saveModel : '.save'
		},

		events: {
			'click @ui.saveModel' : 'saveClicked'
		},

		/**
		 * This behavior listens for a click on .save and cycles through each of the inputs within the views $el that
		 * has a "model_attribute" attribute and calls this.view.set(model_attribute, value)
		 *
		 * VIEW REQUIRES:
		 * - template with inputs containing [model_attribute] attribute key. IE <input model_attribute="first_name">
		 *	- optional success / fail callback for calling once model is saved
		 * @param options
		 */
		initialize: function(options) {
		},

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

			this.setModelAttributes();

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
