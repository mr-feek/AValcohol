/**
 * Created by Feek on 6/6/16.
 */
define([
	'marionette',
	'behaviors/ModelFormSave',
	'models/VendorFactoryModel',
	'templates/factory/vendor.html'
], function (
	Mn,
	ModelFormSave,
	VendorFactoryModel,
	tpl
) {
	var VendorFactoryView = Mn.ItemView.extend({
		template: tpl,
		className: 'panel',

		templateHelpers: function () {
			return {}
		},

		behaviors: {
			ModelFormSave: {
				behaviorClass: ModelFormSave
			},
		},

		events: {
		},

		ui: {
			form: 'form'
		},

		initialize: function(options) {
			this.model = new VendorFactoryModel();

			this.triggerMethod('setModelSaveCallbacks', this.saveSuccess, this.saveFail);
		},

		saveSuccess: function() {
			alert('save success');
			this.render();
		},

		saveFail: function() {
			alert('nope');
		}
	});

	return VendorFactoryView;
});
