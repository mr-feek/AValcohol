/**
 * Created by Feek on 3/22/16.
 */
define([
	'marionette',
	'views/OrdersView',
	'templates/vendor-home.html'
], function (
	Mn,
	OrdersView,
	tpl
) {
	var view = Mn.LayoutView.extend({
		template: tpl,
		tagName: 'div',
		className: '',

		regions: {
			main : '#content'
		},

		initialize: function (options) {
		},

		onRender: function() {
			this.getRegion('main').show(new OrdersView());
		}
	});

	return view;
});
