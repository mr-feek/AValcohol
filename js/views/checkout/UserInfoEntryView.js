define([
	'marionette',
	'App',
	'tpl!templates/checkout/user-info-entry.html'
], function (
	Mn,
	App,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,
		tagName: 'div',
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

		events: {},

		ui: {},

		initialize: function (options) {
			this.model = App.user;
		}
	});

	return view;
});
