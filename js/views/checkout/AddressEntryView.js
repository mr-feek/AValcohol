define([
	'marionette',
	'App',
	'models/UserAddress',
	'tpl!templates/checkout/address-entry.html'
], function (
	Mn,
	App,
	UserAddress,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,
		tagName: 'form',
		className: '',

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

		initialize: function () {
			this.model = App.user.get('address');
		}
	});

	return view;
});
