/**
 * Created by Feek on 6/3/16.
 */
define([
	'backbone',
	'moment',
	'backboneRelational'
], function (
	Backbone,
	moment
) {
	var UserProfile = Backbone.RelationalModel.extend({
		defaults: {
			first_name: null,
			last_name: null,
			phone_number: null,
			date_of_birth: null,
		},

		getFullName: function() {
			return this.get('first_name') + ' ' + this.get('last_name');
		},

		getDateOfBirth: function () {
			return moment(this.get('date_of_birth')).format('LL');
		}
	});

	return UserProfile;
});
