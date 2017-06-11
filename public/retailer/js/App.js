/**
 * Created by Feek on 3/16/16.
 */
define([
	'marionette',
	'shared/js/Brain',
	'shared/js/models/Vendor',
	'shared/js/util/Vent'
], function (
	Mn,
	Brain,
	Vendor,
	Vent
) {
	var App = Mn.Application.extend({
		router: null,

		initialize: function (options) {
		},

		onStart: function () {
			this.listenTo(Vent, 'auth:logout', this.onUserLogout);
		},

		onUserLogout: function() {
			Brain.retrieve('user').destroyClientSide();
			Brain.store('user', Vendor.findOrCreate({}), { ignoreWarning: true});
			this.router.navigate('/retailer/login', { trigger: true })
		}
	});

	return new App();
});
