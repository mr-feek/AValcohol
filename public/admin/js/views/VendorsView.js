/**
 * Created by Feek on 7/2/16.
 */
define([
	'marionette',
	'backgrid',
	'backgrid-paginator',
	'backgrid-filter',
	'collections/Vendors',
	'behaviors/LoadingIndicator',
	'App',
	'tpl!templates/vendors.html'
], function (
	Mn,
	Backgrid,
	BackgridPaginator,
	BackgridFilter,
	Vendors,
	CollectionLoading,
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
			'click @ui.submitSearch' : 'submitSearch'
		},

		ui: {
			search: '.search',
			submitSearch: '.submit-search',
			grid: '.grid',
			paginator: '.paginator',
			filter: '.filter'
		},

		initialize: function (options) {
			this.collection = new Vendors();
			this.triggerMethod('setListener', this.collection);

			this.pageableGrid = new Backgrid.Grid({
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

			this.filter = new Backgrid.Extension.ServerSideFilter({
				collection: this.collection,
				name: 'q',
				placeholder: 'ex: user.profile.email:suresh@avalcohol.com',
				/** @property {function(Object, ?Object=): string} template */
				template: function (data) {
					return '<input type="search" ' + (data.placeholder ? 'placeholder="' + data.placeholder + '"' : '') + ' name="' + data.name + '" ' + (data.value ? 'value="' + data.value + '"' : '') + '/><a class="clear text-center" style="display:block;" data-backgrid-action="clear" href="#">clear search terms</a>';
				},
			});
		},

		onRender: function() {
			this.collection.getFirstPage({reset: true});
			this.ui.search.html(this.filter.render().el);
			this.ui.grid.html(this.pageableGrid.render().el);
			this.ui.paginator.html(this.paginator.render().el);
		},

		submitSearch: function() {
			this.filter.search();
		}
	});

	return AllOrdersView;
});

