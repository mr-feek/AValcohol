define([
	'backbone',
	'shared/js/models/Vendor',
	'backboneRelational'
], function (
	Backbone,
	Vendor
) {
	var VendorHours = Backbone.RelationalModel.extend({
		urlRoot: '/api/vendor/',

		url: function() {
			return this.urlRoot + this.get('vendor').id + '/hours'
		},

		relations: [
			{
				type: Backbone.HasOne,
				key: 'vendor',
				relatedModel: Vendor,
				includeInJSON: Backbone.Model.prototype.idAttribute,
				keyDestination: 'vendor_id', // this might break things in the future, but i want to send vendor_id to backend for now
				reverseRelation: {
					key: 'hours',
					includeInJSON: false,
					type: Backbone.HasMany
				}
			}
		],

		defaults: {
			day_of_week: null,
			open_time: null,
			close_time: null
		},

		validate: function(attrs, options) {
			var errors = [];
			var defaultMessage = "This field is required";

			if (!attrs.day_of_week) {
				errors.push({
					attribute: 'day_of_week',
					message: defaultMessage
				});
			}

			if (!attrs.open_time) {
				errors.push({
					attribute: 'open_time',
					message: defaultMessage
				});
			}

			if (!attrs.close_time) {
				errors.push({
					attribute: 'close_time',
					message: defaultMessage
				});
			}

			return errors.length > 0 ? errors : null;
		}
	});

	return VendorHours;
});
