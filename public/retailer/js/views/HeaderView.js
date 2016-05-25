/**
 * Created by Feek on 3/16/16.
 */
define([
	'marionette',
	'shared/js/util/Vent',
	'tpl!templates/header.html'
], function (
	Mn,
	Vent,
	tpl
) {
	var HeaderView = Mn.ItemView.extend({
		template: tpl,

		templateHelpers: function() {
			return {
				username: this.model.get('name')
			}
		},

		events: {
			'click @ui.settings' : 'showSettings'
		},

		ui: {
			settings: '.settings'
		},

		initialize: function (options) {
			this.model = options.model; // vendor
		},

		showSettings: function() {
			Vent.trigger('settings:show');
		}
	});

	return HeaderView;
});
