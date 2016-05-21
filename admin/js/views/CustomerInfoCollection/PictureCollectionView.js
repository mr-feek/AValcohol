/**
 * Created by Feek on 5/19/16.
 */
define([
	'marionette',
	'tpl!templates/CustomerInfoCollection/picture-collection.html'
], function(
	Mn,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,
		parent: null,

		className: 'collect-customer-info',

		events: {
			'click @ui.clear' : 'clearSignature',
			'click @ui.next' : 'nextClicked',
			'click @ui.previous' : 'previousClicked',
			'change @ui.pictureSelect' : 'pictureSelected'
		},

		ui: {
			'pictureSelect' : '.picture-upload',
			'preview' : '#preview',
			'clear' : '.clear',
			'next' : '.next',
			'previous' : '.previous'
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
			}.bind(this);

			reader.readAsDataURL(e.target.files[0]);
		},

		clearSignature: function() {
			this.ui.signature.jSignature('reset');
		},

		initialize: function(options) {
			this.parent = options.parent;
			_.bindAll(this, 'nextClicked');
		},

		nextClicked(evt) {
			this.parent.trigger('show:next');
		},

		previousClicked(evt) {
			// call behavior
			this.parent.triggerMethod('goToIndex', 0); // hacky but whatever
		}
	});

	return view;
});
