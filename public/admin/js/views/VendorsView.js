/**
 * Created by Feek on 7/2/16.
 */
define([
	'marionette',
	'backgrid',
	'util/Backgrid/BackgridPaginator',
	'util/Backgrid/ServerSideFilter',
	'collections/Vendors',
	'behaviors/LoadingIndicator',
	'behaviors/Backgrid',
	'App',
	'tpl!templates/vendors.html'
], function (
	Mn,
	Backgrid,
	BackgridPaginator,
	ServerSideFilter,
	Vendors,
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

		initialize: function (options) {
			this.collection = new Vendors();
			this.triggerMethod('setListener', this.collection);
			this.initializeBackgridComponents();
		},

		initializeBackgridComponents: function() {
			this.triggerMethod('initializeBackgridComponents', {
				collection: this.collection,
				grid: this.initializeGrid(),
				paginator: new BackgridPaginator(this.collection),
				filter: new ServerSideFilter(this.collection)
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
					}
				],
				collection: this.collection
			});
		}
	});

	return AllOrdersView;
});

