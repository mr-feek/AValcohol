/**
 * Created by Feek on 5/25/16.
 */
define([
	'marionette',
	'behaviors/Modal',
	'behaviors/ModelFormSave',
	'models/EmailCollection',
	'App',
	'tpl!templates/landing/cannot-deliver.html'
], function(
	Mn,
	Modal,
	ModelFormSave,
	EmailCollection,
	app,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,

		behaviors: {
			Modal: {
				behaviorClass: Modal
			},
			ModelFormSave: {
				behaviorClass: ModelFormSave
			}
		},

		initialize: function(options) {
			this.model = new EmailCollection();
			this.triggerMethod('setModelSaveCallbacks', this.saveSuccess, this.saveFail);
		},

		saveSuccess: function() {
			this.$el.html('<h3 class="text-center">Sweet! We\'ll be in touch.</h3>');

			setTimeout(function() {
				app.rootView.ui.modalWrapper.fadeOut();
				this.destroy();
			}.bind(this), 2000);
		},

		saveFail: function() {
			console.error('something went wrong saving this name and email');
		}
	});

	return view;
});
