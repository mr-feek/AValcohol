/**
 * Created by Feek on 3/16/16.
 */
define([
	'marionette',
	'tpl!templates/login.html'
], function (
	Mn,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,
		tagName: 'div',
		className: 'login',

		ui: {
			username: '.username',
			password: '.password',
			login: '.go',
			forgot: '.forgot-password'
		},

		events: {
			'click @ui.login' : 'login',
			'click @ui.forgot' : 'forgotPassword'
		},

		initialize: function (options) {
			this.model = options.model;
			this.listenTo(this.model, "invalid", this.validationError);
		},

		login: function() {
			this.model.set('username', this.ui.username.val());
			this.model.set('password', this.ui.password.val());

			if (this.model.isValid()) {
				this.clearValidationErrors();
				this.model.login();
			}
		},

		// cherry picked from behavior
		validationError: function(model, errors) {
			this.clearValidationErrors();
			_.each(errors, function(error) {
				var $input = this.$el.find('[model_attribute="' + error.attribute + '"]'); // could change this find to search directly from ui inputs.. too tired to figure that out now

				// add error class to label
				$input.parent('label').addClass('error');

				var html = '<small class="error"\>' + error.message + '</small>';
				$input.after(html);
			}.bind(this));
		},

		/**
		 * Removes all errors from the form
		 */
		clearValidationErrors: function() {
			this.$el.find('small.error').remove();
			this.$el.find('label.error').removeClass('error');
		},

		forgotPassword: function() {
			alert('Please contact feek@avalcohol.com to reset your password.');
		}
	});

	return view;
});
