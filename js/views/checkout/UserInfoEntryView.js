define([
	'marionette',
	'App',
	'../../../shared/js/behaviors/ModelFormSave',
	'../../../shared/js/behaviors/ModelSaveAnimation',
	'tpl!templates/checkout/user-info-entry.html'
], function (
	Mn,
	App,
	ModelFormSave,
	ModelSaveAnimation,
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
			},
			ModelSaveAnimation: {
				behaviorClass: ModelSaveAnimation
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
			this.triggerMethod('setModelSaveCallbacks', this.modelSaveSuccess);
		},

		modelSaveSuccess: function(response) {
			this.parent.showNext();
		},
	});

	return view;
});
