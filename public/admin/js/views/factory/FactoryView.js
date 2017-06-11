/**
 * Created by Feek on 6/6/16.
 */
define([
	'marionette',
	'views/factory/VendorFactoryView',
	'tpl!templates/factory/factory.html'
], function (
	Mn,
	VendorFactoryView,
	tpl
) {
	var FactoryView = Mn.LayoutView.extend({
		template: tpl,

		templateHelpers: function () {
			return {}
		},
		 /*
		behaviors: {
			Modal: {
				behaviorClass: Modal
			},
		},
		*/

		events: {
		},

		ui: {
		},

		regions: {
			vendor:	'.vendor'
		},

		initialize: function( options) {
		},

		onRender: function() {
			this.getRegion('vendor').show(new VendorFactoryView());
		}
	});

	return FactoryView;
});
