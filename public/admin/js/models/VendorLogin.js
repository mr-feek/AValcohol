define([
	'backbone'
], function (Backbone) {
	var VendorLogin = Backbone.Model.extend({
		urlRoot: '/api/admin/vendor/login',

		defaults: {}
	});

	return VendorLogin;
});
