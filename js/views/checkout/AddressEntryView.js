define([
	'marionette',
	'App',
	'models/UserAddress',
	'behaviors/ModelFormSave',
	'tpl!templates/checkout/address-entry.html'
], function (
	Mn,
	App,
	UserAddress,
	ModelFormSave,
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

		events: {},

		ui: {},

		initialize: function (options) {
			this.parent = options.parent;
			this.model = App.user.get('address');

			if (!this.model) {
				this.model = UserAddress.findOrCreate({});
			}
		}
	});

	return view;
});
