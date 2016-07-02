/**
 * Created by Feek on 7/2/16.
 */
define([
	'backgrid',
	'backgrid-paginator',
], function (
	Backgrid,
	BackgridPaginator
) {
	var backgridPaginator = Backgrid.Extension.Paginator.extend({
		className: 'pagination-centered',

		initialize: function(collection) {
			this.collection = collection;
		},

		render: function () {
			this.$el.empty();

			var totalPages = this.collection.state.totalPages;

			// Don't render if collection is empty
			if (this.renderMultiplePagesOnly && totalPages <= 1) {
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
	return backgridPaginator;
});