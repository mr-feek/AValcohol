/**
 * Created by Feek on 3/22/16.
 */
define([
	'marionette',
	'templates/vendor-home.html'
], function (
	Mn,
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
			// main populated in controller
		}
	});

	return view;
});
