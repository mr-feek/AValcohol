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
			'change:products' : 'changed'
		},

		events: {},

		ui: {},

		/**
		 * expects cart model
		 * @param options
		 */
		initialize: function (options) {
			this.model = options.model;
			console.log(this.model);
		},

		changed: function() {
			console.log('lol');
			this.render();
		}
	});

	return UserHomeHeaderView;
});
