/**
 * Created by Feek on 6/2/16.
 */
define([
	'marionette',
	'backgrid',
	'backgrid-paginator',
	'collections/Orders',
	'behaviors/CollectionLoading',
	'tpl!templates/all-orders.html'
], function (
	Mn,
	Backgrid,
	BackgridPaginator,
	Orders,
	CollectionLoading,
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
		},


		events: {
			'click @ui.orderShowDetails' : 'showOrderDetails'
		},

		ui: {
			grid: '.grid',
			paginator: '.paginator',
			filter: '.filter',
			orderShowDetails: '.details'
		},

		initialize: function (options) {
			this.collection = new Orders([], {	endpoint: 'all'	});
			this.triggerMethod('setCollection', this.collection);

			this.pageableGrid = new Backgrid.Grid({
				columns: [
					{
						name: 'id',
						label: 'Order ID',
						editable: false,
						cell: Backgrid.IntegerCell.extend({
							orderSeparator: ''
						})
					},
					{
						label: 'Customer Name',
						editable: false,
						cell: Backgrid.StringCell.extend({
							render: function() {
								this.$el.html(this.model.get('user').get('profile').getFullName());
								return this;
							}
						})
					},
					{
						name: 'full_charge_amount',
						label: 'Order Total',
						editable: false,
						cell: Backgrid.NumberCell
					},
					{
						label: 'Vendor Status',
						editable: false,
						cell: Backgrid.StringCell.extend({
							render: function() {
								this.$el.html(this.model.get('status').get('vendor_status'));
								return this;
							}
						})
					},
					{
						label: 'Delivery Status',
						editable: false,
						cell: Backgrid.StringCell.extend({
							render: function() {
								this.$el.html(this.model.get('status').get('delivery_status'));
								return this;
							}
						})
					},
					{
						name: 'created_at',
						label: 'Date Placed',
						editable: false,
						cell: Backgrid.DateCell
					},
					{
						editable: false,
						cell: Backgrid.StringCell.extend({
							render: function() {
								this.$el.html('<a class="details" data-id="' + this.model.id + '">Details</a>');
								return this;
							}
						})
					},
				],
				collection: this.collection
			});

			this.paginator = new Backgrid.Extension.Paginator({
				collection: this.collection
			});
		},

		onRender: function() {
			this.collection.getFirstPage({reset: true});
			this.ui.grid.html(this.pageableGrid.render().el);
			this.ui.paginator.html(this.paginator.render().el);
		},

		showOrderDetails: function(evt) {
			evt.preventDefault();
			var orderId = $(evt.target).data('id');
			console.log('yo ' + orderId);
		}
	});

	return AllOrdersView;
});
