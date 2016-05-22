define([
	'marionette',
	'behaviors/Modal',
	'behaviors/StateManager',
	'views/CustomerInfoCollection/SignatureCollectionView',
	'views/CustomerInfoCollection/PictureCollectionView',
	'tpl!templates/CustomerInfoCollection/parent.html'
], function(
	Mn,
	Modal,
	StateManager,
	SignatureView,
	PictureView,
	tpl
) {
	var view = Mn.LayoutView.extend({
		template: tpl,
		className: 'collect-customer-info',
		regions: {
			'sub-region' : '.sub-region'
		},

		childEvents: {
			'submit:details' : 'submitDeliveryDetails'
		},

		behaviors: {
			Modal: {
				behaviorClass: Modal
			},
			StateManager: {
				behaviorClass: StateManager
			}
		},

		initialize: function(options) {
			var viewsToShow = [
				new SignatureView({
					parent: this,
					model: this.model
				}),
				new PictureView({
					parent: this,
					model: this.model
				})
			];
			this.triggerMethod('setViewFlow', viewsToShow);
			this.triggerMethod('setRegion', this.getRegion('sub-region'));
		},

		submitDeliveryDetails: function() {
			this.model.save().done(function(response) {
				debugger;
			}.bind(this)).fail(function(response) {
				alert('Something went wrong with saving the signature and photo, please try again. If the problem continues, contact your manager ASAP to report the issue to the development team.');
			}.bind(this));
		}
	});

	return view;
});
