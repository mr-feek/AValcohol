/**
 * Created by Feek on 12/11/16.
 */
define([
	'backbone',
	'backboneRelational'
], function(
	Backbone
) {
	return {
		/**
		 * removes locally but does not hit send a network request
		 * @param options
		 */
		destroyClientSide: function (options) {
			this.trigger('destroy');
			Backbone.Relational.store.unregister(this);
		}
	};
});
