/**
 * Created by Feek on 1/5/16.
 *
 * Thanks to the code found here for inspiration! https://github.com/marionettejs/backbone.marionette/issues/2221
 */

define([
	'backbone',
	'marionette'
], function(
	Backbone,
	Marionette
) {
	var collectionLoadIndicator = Marionette.Behavior.extend({
		ui: {
			loading: '.loading-animation'
		},
		/**
		 * Will reflow the foundation equalizer if reflowEqualizer function is implemented on the view
		 */
		initialize: function () {
			this.model = new Backbone.Model;
			this.boilerplate = Marionette.ItemView.extend(this.options);
		},

		/**
		 * View needs to call triggerMethod setCollection and pass the collection as the parameter
		 * before fetching so that we can listen to the correct events and act accordingly
		 * @param collection
		 */
		onSetCollection: function(collection) {
			this.listenTo(collection, "request", this.showLoadingElement);
			this.listenTo(collection, "sync", this.hideLoadingElement);
			this.listenTo(collection, "error", this.hideLoadingElement);
		},

		// this is still showing the empty view!
		showLoadingElement: function () {
			this.view.startBuffering();
			// code from github issue.. not really sure if i want to implement. seems messy
			//this.view.closeChildren();
			//this.view.closeEmptyView();
			//this.loadingView = this.view.addItemView(this.model, this.boilerplate, 0);
			this.view.destroyEmptyView();
			// substr to remove "." from ui value
			var html = '<div class="' + this.ui.loading.substr(1) + '"\>' +
				'\<img src="/img/loading.gif"/>' +
				'\</div>';
			this.$el.prepend(html);
			this.view.endBuffering();
		},

		hideLoadingElement: function (a, b) {
			// since we are subscribing to all error events on the collection, we only want to update the dom when
			// status is 200 (fetch was successful, but may be empty)
			if (b && b.status && b.status != 200) {
				console.error('An error happened that was not an empty backbone collection: ' + JSON.stringify(b, null, 4));
				return;
			}
			//this.view.removeChildView(this.loadingView);
			//delete this.loadingView;
			this.$el.find(this.ui.loading).slideUp(300, function() {
				if (this.view.reflowEqualizer) {
					this.view.reflowEqualizer();
				}
			}.bind(this));

			this.view.checkEmpty();
		}
	});

	return collectionLoadIndicator
});