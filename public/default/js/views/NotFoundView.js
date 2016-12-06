/**
 * Created by Feek on 12/5/16.
 */
define([
	'marionette',
	'App',
	'tpl!templates/not-found.html'
], function (
	Mn,
	app,
	tpl
) {
	var NotFoundView = Mn.ItemView.extend({
		template: tpl,
		className: 'text-center',

		events: {
			'click .js-home' : 'goHome'
		},

		ui: {
		},

		initialize: function( options) {
		},

		goHome: function(evt) {
			evt.preventDefault();
			app.router.navigate('home', { trigger: true });
		}
	});

	return NotFoundView;
});
