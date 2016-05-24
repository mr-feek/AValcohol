define([
	'marionette',
	'App',
	'behaviors/ModelFormSave',
	'behaviors/ModelSaveAnimation',
	'moment',
	'pickaday',
	'tpl!templates/checkout/user-info-entry.html'
], function (
	Mn,
	App,
	ModelFormSave,
	ModelSaveAnimation,
	moment,
	Pickaday,
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

				dob: function() {
					if (!view.model) {	return;	}
					var val = view.model.get('dob');
					return val ? val : ''
				}
			}
		},

		ui: {
			dob : '.dob'
		},

		initialize: function (options) {
			this.parent = options.parent;
			this.model = App.user;
			// attach callback to ModelFormSave behavior
			this.triggerMethod('setModelSaveCallbacks', this.modelSaveSuccess);
		},

		modelSaveSuccess: function(response) {
			this.parent.trigger('show:next');
		},

		onShow: function() {
			var min = moment().subtract(21, 'years').toDate();
			var picker = new Pickaday({
				field: this.ui.dob[0],
				defaultDate: min,
				maxDate: min
			});
		}
	});

	return view;
});
