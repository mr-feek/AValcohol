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
			this.endpoint = options.endpoint;
		},

		linkClicked: function(e) {
			e.preventDefault();
			var link =  e.target.innerHTML.toLowerCase();
			var endpoint = 'home/' + link;
			this.router.navigate(endpoint, {trigger: true});
		},


		onShow: function() {
			this.updateActiveLink();
		},

		/**
		 * THIS CAN BE MORE EFFICIENT
		 * to toggle the active class, cycling through all of them and then adding class active
		 */
		updateActiveLink: function() {
			var view = this;
			this.ui.link.each(function(i, element) {
				$link = $(element);
				var text = $link.text().toLowerCase();
				if (text === view.endpoint) {
					$link.parent().addClass('active');
				} else {
					$link.parent().removeClass('active');
				}
			});
		}
	});

	return ProductCategoriesView;
});
