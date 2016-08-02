define([
	'backbone',
	'shared/js/models/User',
	'shared/js/models/Product',
	'shared/js/models/OrderStatus',
	'shared/js/models/UserAddress',
	'shared/js/models/OrderDeliveryDetails',
	'moment',
	'backboneRelational'
], function (
	Backbone,
	User,
	Product,
	OrderStatus,
	UserAddress,
	DeliveryDetails,
	moment
) {
	var Order = Backbone.RelationalModel.extend({
		urlRoot: '/api/order',
		idAttribute: 'id',

		relations: [
			{
				type: Backbone.HasOne,
				key: 'user',
				relatedModel: User
			},
			{
				type: Backbone.HasMany,
				key: 'products',
				relatedModel: Product
			},
			{
				type: Backbone.HasOne,
				key: 'status',
				relatedModel: OrderStatus,
				includeInJSON: false,
				reverseRelation: {
					key: 'order',
					type: Backbone.HasOne,
					includeInJSON: false
				}
			},
			{
				type: Backbone.HasOne,
				key: 'address',
				relatedModel: UserAddress,
				includeInJSON: true
			},
			{
				type: Backbone.HasOne,
				key: 'delivery_details',
				relatedModel: DeliveryDetails,
				includeInJSON: false,
				reverseRelation: {
					key: 'order',
					type: Backbone.HasOne,
					includeInJSON: 'id'
				}
			}
		],

		defaults: {
			address: null,
			products: null,
			user: null,
			stripe_token: null,
			note: null,
			delivery_details: null,
			terms_and_conditions: null
		},

		initialize: function () {
			_.bindAll(this, 'calculateVendorOrderTotal');
		},

		parse: function(response, xhr) {
			// if this is a request made for a singular model, the model will be stored in this location
			if (response.order) {
				return response.order;
			}

			// otherwise it's part of a collection, and just return response so it doesn't break backbone relational
			return response;
		},

		/**
		 * returns the total amount the vendor will be paid for this order
		 * @returns {string}
		 */
		calculateVendorOrderTotal: function() {
			var total = Number(this.get('vendor_charge_amount')) + Number(this.get('tax_charge_amount'));
			return Number(total).toFixed(2);
		},

		timeSinceOrderWasPlaced: function() {
			var created = moment(this.get('created_at'));
			var diff = created.fromNow();
			return diff;
		},

		timeSinceOrderWasUpdated: function() {
			var updated = moment(this.get('updated_at'));
			var diff = updated.fromNow();
			return diff;
		}
	});

	return Order;
});
