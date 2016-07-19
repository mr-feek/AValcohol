/**
 * Created by Feek on 7/2/16.
 */
define([
	'backgrid',
	'backgrid-filter',
], function (
	Backgrid,
	ServerSideFilter
) {
	var serverSideFilter = Backgrid.Extension.ServerSideFilter.extend({
		initialize: function(collection) {
			this.collection = collection;
		},

		name: 'q',
		placeholder: 'ex: user.profile.email:suresh@avalcohol.com',
		template: function (data) {
			return '<input type="search" ' + (data.placeholder ? 'placeholder="' + data.placeholder + '"' : '') + ' name="' + data.name + '" ' + (data.value ? 'value="' + data.value + '"' : '') + '/><a class="clear text-center" style="display:block;" data-backgrid-action="clear" href="#">clear search terms</a>';
		},
	})
	return serverSideFilter;
});