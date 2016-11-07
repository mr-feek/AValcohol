/**
 * Created by Feek on 5/19/16.
 */
define([
	'marionette',
	'templates/CustomerInfoCollection/picture-collection.html'
], function(
	Mn,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,
		parent: null,
		className: 'collect-customer-info',

		events: {
			'click @ui.previous' : 'previousClicked',
			'click @ui.submit' : 'submitClicked',
			'change @ui.pictureSelect' : 'pictureSelected'
		},

		ui: {
			'pictureSelect' : '.picture-upload',
			'preview' : '#preview',
			'previous' : '.previous',
			'submit' : '.submit'
		},

		/**
		 * load it into the previewer
		 * @param e
		 */
		pictureSelected: function(e) {
			var reader = new FileReader();

			reader.onload = function(e) {
				this.ui.preview.attr('src', e.target.result);
				this.ui.preview.fadeIn();
				this.model.set('photoData', e.target.result);
				this.enableSubmitButton();
			}.bind(this);

			reader.readAsDataURL(e.target.files[0]);
		},

		initialize: function(options) {
			this.parent = options.parent;
			_.bindAll(this, 'previousClicked');
		},

		enableSubmitButton: function() {
			this.ui.submit.removeClass('disabled');
		},

		submitClicked: function() {
			if (this.ui.submit.hasClass('disabled')) {
				return;
			}
			this.triggerMethod('submit:details');
		},

		previousClicked(evt) {
			// call behavior
			this.parent.triggerMethod('goToIndex', 0); // hacky but whatever
		}
	});

	return view;
});
