/**
 * Created by Feek on 8/3/16.
 */
define([
	'marionette',
	'shared/js/models/Vendor',
	'behaviors/LoadingIndicator',
	'tpl!templates/vendor-hours.html'
], function (
	Mn,
	Vendor,
	LoadingIndicator,
	tpl
) {
	var VendorHoursView = Mn.ItemView.extend({
		template: tpl,

		templateHelpers: function () {
			return {
				vendor: this.model
			}
		},

		behaviors: {
			LoadingIndicator: {
				behaviorClass: LoadingIndicator
			},
		},


		events: {
		},

		ui: {
		},

		initialize: function(options) {
			this.model = new Vendor({id : options.id});
			this.triggerMethod('setListener', this.model);
			this.model.fetch().done(this.render);
		}
	});

	return VendorHoursView;
});
