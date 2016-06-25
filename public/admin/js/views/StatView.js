/**
 * Created by Feek on 6/13/16.
 */
define([
	'marionette',
	'behaviors/LoadingIndicator',
	'models/stats',
	'tpl!templates/dashboard.html'
], function (
	Mn,
	CollectionLoading,
	StatsModel,
	tpl
) {
	var StatView = Mn.ItemView.extend({
		template: tpl,
		className: 'row',

		behaviors: {
			CollectionLoading: {
				behaviorClass: CollectionLoading
			},
		},

		events: {
		},

		ui: {
		},

		initialize: function( options) {
			this.model = new StatsModel();
			this.triggerMethod('setListener', this.model);
		},

		onShow: function() {
			this.model.fetch().done(this.render.bind(this));
		}
	});

	return StatView;
});
