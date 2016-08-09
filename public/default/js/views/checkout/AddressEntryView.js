define([
	'marionette',
	'App',
	'shared/js/models/UserAddress',
	'behaviors/ModelFormSave',
	'behaviors/ModelSaveAnimation',
	'tpl!templates/checkout/address-entry.html'
], function (
	Mn,
	App,
	UserAddress,
	ModelFormSave,
	ModelSaveAnimation,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,
		tagName: 'form',
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
				street: function() {
					if (!view.model) {	return;	}
					var val = view.model.get('street');
					return val ? val : '';
				},

				apartmentNumber: function() {
					if (!view.model) {	return;	}
					var val = view.model.get('apartment_number');
					return val ? val : '';
				},

				city: function() {
					if (!view.model) {	return;	}
					var val = view.model.get('city');
					return val ? val : '';
				},

				state: function() {
					if (!view.model) {	return;	}
					var val = view.model.get('state');
					return val ? val : '';
				},

				zipcode: function() {
					if (!view.model) {	return;	}
					var val = view.model.get('zipcode');
					return val ? val : '';
				}
			}
		},

		events: {
			'click @ui.back' : 'goBackOneViewInFlow'
		},

		ui: {
			'back' : '.back'
		},

		initialize: function (options) {
			this.parent = options.parent;
			this.model = App.user.get('address');

			// attach callback to ModelFormSave behavior
			this.triggerMethod('setModelSaveCallbacks', this.modelSaveSuccess);
		},

		modelSaveSuccess: function(response) {
			if (response.success === true) {
				this.parent.trigger('show:next');
				return;
			}

			this.parent.showErrorMessage(response.message);
		},

		goBackOneViewInFlow: function() {
			this.parent.goToViewBasedOnName('user');
		}
	});

	return view;
});
