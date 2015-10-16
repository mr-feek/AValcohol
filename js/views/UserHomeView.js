define([
	'marionette',
	'tpl!templates/user-home.html'
], function (Mn,
			 tpl) {
	var UserHomeView = Mn.ItemView.extend({
		template: tpl,

		events: {},

		ui: {},

		initialize: function (options) {
			console.log('user home view init');
		},

		onShow: function() {
			console.log(this.$el);
		}
	});

	return UserHomeView;
});
