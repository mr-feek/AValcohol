define([
	'marionette',
	'templates/'
], function(
	Mn,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,

		templateHelpers: function() {
			return {
				
			}
		},

		behaviors: {
			Modal: {
				behaviorClass: Modal
			},
		},

		events: {
		},

		ui: {
		},

		initialize: function(options) {
		}
	});

	return view;
});
