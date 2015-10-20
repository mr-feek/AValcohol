define([
	'marionette',
	'tpl!templates/user-home-header.html'
], function (
	Mn,
	tpl
) {
	var UserHomeHeaderView = Mn.ItemView.extend({
		template: tpl,
		tagName: 'div',
		className: '',

		templateHelpers: function() {
			var view = this;

			return {
				number: view.model.get('products').length
			}
		},

		modelEvents: {
			'change:products' : 'productsChanged'
		},

		events: {},

		ui: {
			numProducts : '.num-products'
		},

		/**
		 * expects cart model
		 * @param options
		 */
		initialize: function (options) {
			this.model = options.model;
		},

		productsChanged: function() {
			var number = this.model.get('products').length;
			this.ui.numProducts.html(number);
		}
	});

	return UserHomeHeaderView;
});
