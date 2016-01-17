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
					var val = view.model.get('first');
					return val ? val : '';
				},

				last: function() {
					if (!view.model) {	return;	}
					var val = view.model.get('last');
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
			this.model = App.user;
			// attach callback to ModelFormSave behavior
			this.triggerMethod('setModelSaveCallbacks', this.modelSaveSuccess, this.modelSaveFail);
		},

		modelSaveSuccess: function(response) {
			debugger;
		},

		modelSaveFail: function(response) {
			console.error('something went wrong: ' + response.responseText);
		}
	});

	return view;
});