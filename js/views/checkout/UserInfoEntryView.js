define([
	'marionette',
	'App',
	'behaviors/ModelFormSave',
	'tpl!templates/checkout/user-info-entry.html'
], function (
	Mn,
	App,
	ModelFormSave,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,
		tagName: 'div',
		className: '',
		parent: null,

		behaviors: {
			ModelFormSave: {
				behaviorClass: ModelFormSave
			}
		},

		templateHelpers: function() {
			var view = this;

			return {
				first: function() {
					if (!view.model) {	return;	}
					var val = view.model.get('first_name');
					return val ? val : '';
				},

				last: function() {
					if (!view.model) {	return;	}
					var val = view.model.get('last_name');
					return val ? val : '';
				},

				phone: function() {
					if (!view.model) {	return;	}
					var val = view.model.get('phone_number');
					return val ? val : '';
				},

				email: function() {
					if (!view.model) {	return;	}
					var val = view.model.get('email');
					return val ? val : '';
				},
			}
		},

		initialize: function (options) {
			this.parent = options.parent;
			this.model = App.user;
			// attach callback to ModelFormSave behavior
			this.triggerMethod('setModelSaveCallbacks', this.modelSaveSuccess, this.modelSaveFail);
		},

		modelSaveSuccess: function(response) {
			this.parent.showNext();
		},

		modelSaveFail: function(response) {
			var attribute = Object.keys(response.responseJSON)[0];
			var message = response.responseJSON[attribute][0];

			var error = {
				attribute: attribute,
				message: message
			};

			this.triggerMethod('showValidationErrors', this.model, [error])
		}
	});

	return view;
});
