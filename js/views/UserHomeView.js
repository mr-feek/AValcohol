define([
	'marionette',
	'views/ProductCategoriesView',
	'tpl!templates/user-home.html'
], function (Mn,
			 ProductCategoriesView,
			 tpl) {
	var UserHomeView = Mn.LayoutView.extend({
		template: tpl,

		events: {},

		ui: {},

		regions: {
			sidebar : '#sidebar'
		},

		initialize: function (options) {
		},

		onShow: function() {
			this.getRegion('sidebar').show(new ProductCategoriesView());
		}
	});

	return UserHomeView;
});
