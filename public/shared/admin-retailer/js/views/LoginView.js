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
			container: '.login-container',
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

			this.listenTo(app.session, 'invalid', this.validationError);
			this.listenTo(app.session, 'error', this.onLoginUnsuccessful);

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
				this.clearErrors();
				app.session.login({
					email: this.ui.email.val(),
					password: this.ui.password.val()
				});
			}
		},

		// cherry picked from behavior
		validationError: function(model, errors) {
			this.clearErrors();
			_.each(errors, function(error) {
				var $input = this.$el.find('[model_attribute="' + error.attribute + '"]');
				
				$input.addClass('is-invalid-input');

				var html = '<span class="form-error is-visible"\>' + error.message + '</span>';
				$input.after(html);
			}.bind(this));
		},

		/**
		 * Removes all errors from the form
		 */
		clearErrors: function() {
			this.ui.email.removeClass('is-invalid-input');
			this.ui.password.removeClass('is-invalid-input');
			this.ui.container.find('.js-error-alert').remove();
			this.ui.container.find('.form-error').remove();
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
		},

		onLoginUnsuccessful: function() {
			this.ui.container.prepend(
				'<div class="alert callout js-error-alert">' +
					'<p><i class="fi-alert"></i> No login was found with the supplied email and password</p>' +
				'</div>'
			);
			this.ui.email.addClass('is-invalid-input');
			this.ui.password.addClass('is-invalid-input');
		}
	});

	return view;
});
