define([
	'marionette',
	'App',
	'tpl!templates/product-categories.html'
], function (
	Mn,
	App,
	tpl
) {
	var ProductCategoriesView = Mn.ItemView.extend({
		template: tpl,

		events: {
			'click @ui.link' : 'linkClicked'
		},

		ui: {
			link: 'a'
		},

		initialize: function (options) {
			this.router = App.router;
		},

		linkClicked: function(e) {
			e.preventDefault();
			var link =  e.target.innerHTML.toLowerCase();
			var endpoint = 'home/' + link;
			this.router.navigate(endpoint, {trigger: true});
		}
	});

	return ProductCategoriesView;
});
