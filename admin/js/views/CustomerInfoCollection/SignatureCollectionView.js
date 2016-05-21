/**
 * Created by Feek on 5/19/16.
 */
define([
	'marionette',
	'jSignature',
	'tpl!templates/CustomerInfoCollection/signature-collection.html'
], function(
	Mn,
	jSignature,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,
		parent: null,
		className: 'collect-customer-info',

		events: {
			'click @ui.clear' : 'clearSignature',
			'click @ui.next' : 'nextClicked'
		},

		ui: {
			'signature' : '#signature-box',
			'clear' : '.clear',
			'next' : '.next'
		},

		onShow: function() {
			// for some reason this is needed in order for the box to resize properly
			setTimeout(function() {
				this.ui.signature.jSignature();
			}.bind(this), 1);
		},

		clearSignature: function() {
			this.ui.signature.jSignature('reset');
		},

		initialize: function(options) {
			this.parent = options.parent;
			_.bindAll(this, 'nextClicked');
		},

		nextClicked(evt) {
			if (this.ui.signature.jSignature('getData', 'native').length === 0) {
				alert('no signature detected');
				return;
			}

			this.parent.trigger('show:next');
			// set signature data on model
			var svgData = this.ui.signature.jSignature('getData', 'svgbase64')[1];
			this.model.set('signatureSVGData', svgData);
		}
	});

	return view;
});
