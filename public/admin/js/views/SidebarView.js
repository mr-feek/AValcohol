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
		className: 'sidebar',

		events: {
			'click @ui.stat' 			: 'showStats',
			'click @ui.ready' 			: 'showReadyOrders',
			'click @ui.outForDelivery' 	: 'showOutForDeliveryOrders',
			'click @ui.allOrders' 		: 'showAllOrders',
			'click @ui.vendor' 			: 'showVendors',
			'click @ui.factory'			: 'showFactory'
		},

		ui: {
			allLinks 		: '.icon',
			stat			: '.stat',
			ready 			: '.ready',
			outForDelivery 	: '.out-for-delivery',
			allOrders 		: '.all-orders',
			vendor			: '.vendor',
			factory 		: '.factory'
		},

		initialize: function() {
			this.listenTo(this, 'showing', this.switchActiveLink);
		},

		showStats: function() {
			App.router.navigate('admin/dashboard/stats', {trigger: true});
		},

		showReadyOrders: function() {
			App.router.navigate('admin/dashboard/ready', {trigger: true});
		},

		showOutForDeliveryOrders: function() {
			App.router.navigate('admin/dashboard/out', {trigger: true});
		},

		showAllOrders: function() {
			App.router.navigate('admin/dashboard/all', {trigger: true});
		},

		showFactory: function() {
			App.router.navigate('admin/dashboard/factory', {trigger: true});
		},

		showVendors: function() {
			App.router.navigate('admin/dashboard/vendors', {trigger: true});
		},

		/**
		 * removes the current active and adds active to the passed in element
		 * @param toBeActive
		 */
		switchActiveLink: function(key) {
			var toBeActive;

			switch (key) {
				case 'stat' :
					toBeActive = this.ui.stat;
					break;
				case 'out' :
					toBeActive = this.ui.outForDelivery;
					break;
				case 'ready' :
					toBeActive = this.ui.ready;
					break;
				case 'all' :
					toBeActive = this.ui.allOrders;
					break;
				case 'vendor' :
					toBeActive = this.ui.vendor;
					break;
				case 'factory' :
					toBeActive = this.ui.factory;
					break;
			}

			this.ui.allLinks.removeClass('active');
			toBeActive.addClass('active');
		}
	});

	return view;
});
