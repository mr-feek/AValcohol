define([
	'marionette',
	'shared/js/util/Vent',
	'tpl!templates/landing/header.html'
], function (
	Mn,
	Vent,
	tpl
) {
	var HeaderView = Mn.ItemView.extend({
		template: tpl,

		events: {
			'click @ui.scrollLink' : 'scrollLinkClicked',
            'click @ui.join' : 'joinClicked'
		},

		ui: {
			scrollLink : '.top-bar-section .right li', // links that should initiate scrolls
            join : '.join'
		},

		initialize: function (options) {
		},

		scrollLinkClicked: function(evt) {
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
		},

        joinClicked: function(evt) {
            Vent.trigger('root:user:add');
        }
	});

	return HeaderView;
});
