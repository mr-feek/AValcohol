define([
	'marionette',
	'views/HeaderView',
	'views/HomeView',
    'views/AddUserView',
	'util/Vent',
	'tpl!templates/root.html'
], function(
	Mn,
	HeaderView,
	HomeView,
    AddUserView,
	Vent,
	tpl
) {
	var RootView = Mn.LayoutView.extend({
		template: tpl,
		el: 'body',

		events: {
		},

		ui: {
		},

		regions: {
			header: 'header',
			main: '#main',
            dialog: '#dialog'
		},

		initialize: function(options) {
			Vent.on('root:scrollTo', this.scrollTo);
            _.bindAll(this, 'showUserJoin');
            Vent.on('root:user:add', this.showUserJoin);
		},

		onRender: function() {
			this.getRegion('header').show(new HeaderView());
			this.getRegion('main').show(new HomeView());
		},

		scrollTo: function(selector) {
			$('html, body').animate({
				scrollTop: $(selector).offset().top}, 500);
		},

        // this probably shouldn't be in here, but will work for now...
        showUserJoin: function() {
            console.log('called');
            this.getRegion('dialog').show(new AddUserView());
            $(this.regions.dialog).foundation('reveal', 'open');
        }
	});

	return RootView;
});
