/**
 * Created by Feek on 3/15/16.
 */
define([
	'marionette',
	'../util/Vent'
], function (
	Mn,
	Vent
) {
	var Modal = Mn.Behavior.extend({
		/**
		 * This behavior handles the behavior for a modal
		 *
		 * VIEW REQUIRES:
		 * - template with .close (close button)
		 * - template with .background (background div covering page)
		 *
		 * @param options
		 */
		initialize: function(options) {
		},

		events: {
			'click @ui.close' : 'close',
			'click @ui.cancel' : 'close',
			'click @ui.background' : 'close'
		},

		ui: {
			close : '.close',
			background: '.background',
			cancel: '.cancel'
		},

		close: function() {
			Vent.trigger('modal:close');
		}
	});

	return Modal;
});