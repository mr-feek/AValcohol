/**
 * Created by Feek on 7/2/16.
 */
define([
	'marionette',
	'backgrid',
	'util/Backgrid/BackgridPaginator',
	'util/Backgrid/ServerSideFilter',
	'collections/Vendors',
	'models/VendorLogin',
	'behaviors/LoadingIndicator',
	'behaviors/Backgrid',
	'App',
	'templates/vendors.html'
], function (
	Mn,
	Backgrid,
	BackgridPaginator,
	ServerSideFilter,
	Vendors,
	VendorLogin,
	CollectionLoading,
	BackgridBehavior,
	app,
	tpl
) {
	var AllOrdersView = Mn.ItemView.extend({
		template: tpl,

		templateHelpers: function () {
			return {}
		},

		behaviors: {
			CollectionLoading: {
				behaviorClass: CollectionLoading
			},
			BackgridBehavior: {
				behaviorClass: BackgridBehavior
			},
		},

		events: {
			'click @ui.loginAsVendor' : 'loginAsVendor',
		},

		ui: {
			loginAsVendor: '.login-as-vendor'
		},

		initialize: function (options) {
			this.collection = new Vendors();
			this.triggerMethod('setListener', this.collection);
			this.initializeBackgridComponents();
		},

		initializeBackgridComponents: function() {
			this.triggerMethod('initializeBackgridComponents', {
				collection: this.collection,
				grid: this.initializeGrid(),
				paginator: new BackgridPaginator({collection:this.collection}),
				filter: new ServerSideFilter(this.collection)
			});
		},

		loginAsVendor: function(evt) {
			evt.preventDefault();
			var vendorId = $(evt.target).data('id');

			new VendorLogin({id : vendorId}).fetch().done(function(response) {
				var token = response.token;
				app.session.set('token', token);
				app.session.persist('token', token);
				window.location = '/retailer/dashboard'
			});
		},

		initializeGrid: function() {
			return new Backgrid.Grid({
				columns: [
					{
						name: 'id',
						label: 'Vendor ID',
						editable: false,
						cell: Backgrid.IntegerCell.extend({
							orderSeparator: ''
						})
					},
					{
						name: 'name',
						label: 'Vendor Name',
						editable: false,
						sortable: false,
						cell: Backgrid.StringCell
					},
					{
						name: 'address',
						label: 'Vendor Address',
						editable: false,
						sortable: false,
						cell: Backgrid.StringCell
					},
					{
						name: 'phone_number',
						label: 'Phone Number',
						editable: false,
						sortable: false,
						cell: Backgrid.StringCell
					},
					{
						name: 'delivery_zone_id',
						label: 'Delivery Zone ID',
						editable: false,
						sortable: false,
						cell: Backgrid.IntegerCell
					},
					{
						label: '',
						editable: false,
						sortable: false,
						cell: Backgrid.StringCell.extend({
							render: function() {
								this.$el.html('<a class="login-as-vendor button" data-id="' + this.model.id + '">Login</a>');
								return this;
							}
						})
					},
				],
				collection: this.collection
			});
		}
	});

	return AllOrdersView;
});

