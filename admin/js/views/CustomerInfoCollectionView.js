define([
	'marionette',
	'behaviors/Modal',
	'jSignature',
	'tpl!templates/customer-info-collection.html'
], function(
	Mn,
	Modal,
	jSignature,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,
		className: 'collect-customer-info',

		behaviors: {
			Modal: {
				behaviorClass: Modal
			}
		},

		events: {
			'click @ui.clear' : 'clearSignature'
		},

		ui: {
			'signature' : '#signature-box',
			'clear' : '.clear'
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
		}
	});

	return view;
});
