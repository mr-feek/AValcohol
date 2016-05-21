/**
 * Created by Feek on 5/21/16.
 */
define([
    'backbone',
	'backboneRelational'
], function(
    Backbone
) {
    var model = Backbone.RelationalModel.extend({
        urlRoot: '/api/',

		url: function() {
			return '/api/admin/order/' + this.get('order').get('id') + '/delivery-details'
		},
        
        defaults: {
            signatureSVGData: null,
			photoData: null,
			order: null
        }
        
    });

    return model;
});
