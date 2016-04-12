/**
 * Created by Feek on 3/22/16.
 */
define([
	'marionette',
	'views/SidebarView',
	'views/ReadyOrdersView',
	'tpl!templates/vendor-home.html'
], function (
	Mn,
	SidebarView,
	ReadyOrdersView,
	tpl
) {
	var view = Mn.LayoutView.extend({
		template: tpl,
		tagName: 'div',
		className: '',

		regions: {
			sidebar: '#sidebar',
			main : '#content'
		},

		initialize: function (options) {
		},

		onRender: function() {
			this.getRegion('sidebar').show(new SidebarView());
			this.getRegion('main').show(new ReadyOrdersView());
		}
	});

	return view;
});
