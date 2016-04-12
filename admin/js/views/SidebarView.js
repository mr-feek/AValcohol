/**
 * Created by Feek on 3/22/16.
 */

define([
	'marionette',
	'tpl!templates/sidebar.html'
], function (
	Mn,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,
		tagName: 'div',
		className: '',

		templateHelpers: function() {
			var view = this;

			return {

			}
		},

		events: {
			'click @ui.ready' : 'showReadyOrders',
			'click @ui.outForDelivery' : 'showOutForDeliveryOrders'
		},

		ui: {
			ready :'.ready',
			outForDelivery : '.out-for-delivery'
		},

		initialize: function (options) {
		},

		showReadyOrders: function() {

		},

		showOutForDeliveryOrders: function() {

		}
	});

	return view;
});
