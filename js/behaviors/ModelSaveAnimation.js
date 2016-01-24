/**
 * Created by Feek on 1/21/16.
 */

define([
	'marionette'
], function (
	Mn
) {
	var ModelSaveAnimation = Mn.Behavior.extend({

		initialize: function(options) {	},

		onShow: function() {
			this.listenTo(this.view.model, 'request', this.showLoadingAnimation);
			this.listenTo(this.view.model, 'sync', this.hideLoadingAnimation);
			this.listenTo(this.view.model, 'error', this.hideLoadingAnimation);
		},

		showLoadingAnimation: function() {
			var html =
				'<div class="loading text-center"\>' +
					'\<img src="/img/loading.gif"/>' +
					'<p>Loading...</p>' +
				'\</div>';
			this.$el.prepend(html);
		},

		hideLoadingAnimation: function() {
			this.$el.find('.loading').fadeOut();
		}
	});

	return ModelSaveAnimation
});
