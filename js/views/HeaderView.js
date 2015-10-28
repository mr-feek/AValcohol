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
			'click @ui.link': 'linkClicked'
		},

		ui: {
			link: '.top-bar-section .right *' // either li or anchor
		},

		initialize: function (options) {
		},

		linkClicked: function (evt) {
			var selector = '';
			switch (evt.target.className) {
				case 'customer':
					selector = '.customers';
					break;
				/*
				 case 'restaurant':
				 selector = '.restaurants';
				 break;
				 */
				case 'retailer':
					selector = '.retailers';
					break;
				case 'button round contact':
					selector = ".contact-us";
					break;
			}

			Vent.trigger('root:scrollTo', selector);
		}
	});

	return HeaderView;
});
