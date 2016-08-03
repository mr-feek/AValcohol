/**
 * Created by Feek on 8/3/16.
 */
define([
	'marionette',
	'shared/js/models/Vendor',
	'shared/js/models/VendorHours',
	'behaviors/LoadingIndicator',
	'tpl!templates/vendor-hours.html'
], function (
	Mn,
	Vendor,
	VendorHours,
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
			}
		},


		events: {
			'click @ui.save' : 'createNewVendorHoursEntry'
		},

		ui: {
			'dayOfWeek' : '.day-of-week',
			'openTime' : '.open-time',
			'closeTime' : '.close-time',
			'save' : '.save'
		},

		initialize: function(options) {
			this.model = Vendor.findOrCreate({id : options.id});
			this.triggerMethod('setListener', this.model);
			this.model.fetch().done(this.render);
		},

		createNewVendorHoursEntry: function(evt) {
			evt.preventDefault();

			var vendorHoursModel = VendorHours.findOrCreate({
				day_of_week : this.ui.dayOfWeek.val(),
				open_time : this.ui.openTime.val(),
				close_time : this.ui.closeTime.val(),
				vendor : this.model
			});

			if (vendorHoursModel.isValid()) {
				vendorHoursModel.save();
			} else {
				alert('invalid data entered.');
			}
		}
	});

	return VendorHoursView;
});
