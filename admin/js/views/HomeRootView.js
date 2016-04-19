/**
 * Created by Feek on 3/22/16.
 */
define([
	'marionette',
	'views/SidebarView',
	'tpl!templates/vendor-home.html'
], function (
	Mn,
	SidebarView,
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
			// main populated in controller
		}
	});

	return view;
});
