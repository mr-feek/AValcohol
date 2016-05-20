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
				new SignatureView({		parent: this	}),
				new PictureView({	parent: this	})
			];
			this.triggerMethod('setViewFlow', viewsToShow);
			this.triggerMethod('setRegion', this.getRegion('sub-region'));
		}
	});

	return view;
});
