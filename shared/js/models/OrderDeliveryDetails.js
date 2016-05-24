/**
 * Created by Feek on 5/21/16.
 */
define([
    'backbone',
	'../../../shared/js/models/Order',
	'backboneRelational'
], function(
    Backbone,
	Order
) {
    var model = Backbone.RelationalModel.extend({
        urlRoot: '/api/',
		idAttribute: 'order_id',

		url: function() {
			return '/api/admin/order/' + this.get('order').get('id') + '/delivery-details'
		},

		// can i define relations in both files?
		relations: [
			{
				type: Backbone.HasOne,
				key: 'order',
				keyDestination: 'order_id',
				relatedModel: Order,
				includeInJSON: Backbone.Model.prototype.idAttribute
			}
		],
        
        defaults: {
            signature: null, // svg data
			photoData: null,
			order: null
        },

		parse: function(response, xhr) {
			return response.delivery_details;
		}
    });

    return model;
});
