/**
 * Created by Feek on 6/2/16.
 */
define([
	'marionette',
	'backgrid',
	'backgrid-paginator',
	'collections/Orders',
	'behaviors/CollectionLoading',
	'views/OrderDetailView',
	'App',
	'tpl!templates/all-orders.html'
], function (
	Mn,
	Backgrid,
	BackgridPaginator,
	Orders,
	CollectionLoading,
	OrderDetailView,
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
						sortable: false,
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
						sortable: false,
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
						sortable: false,
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
						label: '',
						editable: false,
						sortable: false,
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

			/**
			 * to do: migrate to new file when want to reuse
			 */
			var backgridPaginator = Backgrid.Extension.Paginator.extend({
				className: "pagination-centered",

				render: function() {
					this.$el.empty();

					var totalPages = this.collection.state.totalPages;

					// Don't render if collection is empty
					if(this.renderMultiplePagesOnly && totalPages <= 1) {
						return this;
					}

					if (this.handles) {
						for (var i = 0, l = this.handles.length; i < l; i++) {
							this.handles[i].remove();
						}
					}

					var handles = this.handles = this.makeHandles();

					var ul = document.createElement("ul");
					ul.className = 'pagination';
					for (var i = 0; i < handles.length; i++) {
						ul.appendChild(handles[i].render().el);
					}

					this.el.appendChild(ul);

					return this;
				}
			});

			this.paginator = new backgridPaginator({
				collection: this.collection,
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

			app.rootView.getRegion('modalRegion').show(new OrderDetailView({	model:	this.collection.get(orderId)	}));
		}
	});

	return AllOrdersView;
});
