/**
 * Created by Feek on 3/16/16.
 */
define([
	'marionette',
	'util/Vent',
	'tpl!templates/header.html'
], function (
	Mn,
	Vent,
	tpl
) {
	var HeaderView = Mn.ItemView.extend({
		template: tpl,

		events: {
		},

		ui: {
		},

		initialize: function (options) {
		}
	});

	return HeaderView;
});
