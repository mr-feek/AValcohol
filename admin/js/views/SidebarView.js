/**
 * Created by Feek on 3/22/16.
 */

define([
	'marionette',
	'App',
	'tpl!templates/sidebar.html'
], function (
	Mn,
	App,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,
		tagName: 'div',
		className: '',

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

		onShow: function() {

		},

		showReadyOrders: function() {
			this.ui.ready.addClass('active');
			this.ui.outForDelivery.removeClass('active');
			App.router.navigate('admin/dashboard/ready', {trigger: true});
		},

		showOutForDeliveryOrders: function() {
			this.ui.outForDelivery.addClass('active');
			this.ui.ready.removeClass('active');
			App.router.navigate('admin/dashboard/out', {trigger: true});
		},
	});

	return view;
});
