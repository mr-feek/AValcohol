define([
	'marionette',
	'tpl!templates/add-user.html'
], function(
	Mn,
	tpl
) {
	var AddUserView = Mn.ItemView.extend({
		template: tpl,

		events: {
            'click @ui.join' : 'joinClicked'
		},

		ui: {
            'email' : '.email',
            'password' : '.password',
            'join' : '.signup'
		},

		initialize: function(options) {
		},

        joinClicked: function () {
            var emailVal = this.ui.email.val();
            var passwordVal = this.ui.password.val();

            $.ajax({
                url: 'php/api/user/add',
                type: 'POST',
                dataType: 'json',
                data: {
                    email: emailVal,
                    password: passwordVal
                }
            }).done(function (result) {
                // lets change the route here and try out backbone router
                // to go to user home
            }).fail(function (result) {
                // show error
            });
        }
	});

	return AddUserView;
});
