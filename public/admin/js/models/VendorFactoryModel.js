define([
	'backbone'
], function (Backbone) {
	var VendorFactoryModel = Backbone.Model.extend({
		urlRoot: '/api/admin/vendor',

		defaults: {
			email: null,
			password: null,
			name: null,
			address: null,
			phone_number: null,
			delivery_zone_id: null
		}
	});

	return VendorFactoryModel;
});
