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
			'click @ui.ready' 			: 'showReadyOrders',
			'click @ui.outForDelivery' 	: 'showOutForDeliveryOrders',
			'click @ui.allOrders' 		: 'showAllOrders'
		},

		ui: {
			ready 			: '.ready',
			outForDelivery 	: '.out-for-delivery',
			allOrders 		: '.all-orders'
		},

		initialize: function (options) {
		},

		onShow: function() {

		},

		/**
		 * NEED TO OPTIMIZE THIS SHIT AND FIX IT
		 */

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

		showAllOrders: function() {
			this.ui.allOrders.addClass('active');
			this.ui.ready.removeClass('active');
			App.router.navigate('admin/dashboard/all', {trigger: true});
		}
	});

	return view;
});
