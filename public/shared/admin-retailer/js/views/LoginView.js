/**
 * Created by Feek on 3/16/16.
 */
define([
	'marionette',
	'shared/js/util/Vent',
	'behaviors/LoadingIndicator',
	'App',
	'tpl!../../js/templates/login.html'
], function (
	Mn,
	Vent,
	CollectionLoading,
	app,
	tpl
) {
	var view = Mn.ItemView.extend({
		template: tpl,
		tagName: 'div',
		className: 'login',

		behaviors: {
			CollectionLoading: {
				behaviorClass: CollectionLoading
			}
		},

		ui: {
			email: '.email',
			password: '.password',
			login: '.go',
			forgot: '.forgot-password'
		},

		events: {
			'click @ui.login' : 'login',
			'keyup @ui.password' : 'passwordCharacterTyped',
			'click @ui.forgot' : 'forgotPassword'
		},

		initialize: function (options) {
			if (!options.loginSuccessCallback) {
				console.error('it is required to pass a login success callback to the login view');
				return;
			}
			this.model = options.model;
			this.listenTo(this.model, 'invalid', this.validationError);

			this.triggerMethod('setListener', app.session);
			this.triggerMethod('setListener', this.model);

			_.bindAll(this, 'passwordCharacterTyped');
			this.listenTo(Vent, 'user:authenticated', this.onLoginSuccess);
		},

		passwordCharacterTyped: function(evt) {
			if (evt.keyCode === 13) {
				// enter was pressed
				this.login();
			}
		},

		/**
		 * submits a login request with supplied email and password
		 */
		login: function() {
			// only setting these to piggyback the validation
			app.session.set('email', this.ui.email.val());
			app.session.set('password', this.ui.password.val());
			if (app.session.isValid()) {
				this.clearValidationErrors();
				app.session.login({
					email: this.ui.email.val(),
					password: this.ui.password.val()
				});
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

		/**
		 * TODO
		 */
		forgotPassword: function() {
			alert('Please contact feek@avalcohol.com to reset your password.');
		},

		onLoginSuccess: function() {
			var callback = this.getOption('loginSuccessCallback');
			this.model.fetch().done(function(response) {
				callback(response);
			});
		}
	});

	return view;
});
