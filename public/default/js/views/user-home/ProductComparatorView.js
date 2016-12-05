/**
 * Created by Feek on 12/4/16.
 */
define([
    'marionette',
    'util/Brain',
    'tpl!templates/user-home/product-comparator.html'
], function (
    Mn,
    Brain,
    tpl
) {
    return Mn.ItemView.extend({
        template: tpl,

        templateHelpers: function () {
            return {}
        },
        /*
         behaviors: {
         Modal: {
         behaviorClass: Modal
         },
         },
         */
        events: {
            'change @ui.select-menu' : 'onSelectMenuChange'
        },

        ui: {
            'select-menu' : 'select'
        },

        initialize: function (options) {
        },

        onSelectMenuChange: function(event) {
            var value = event.target.value;

            var products = Brain.retrieve('products');

            switch(value) {
                case 'price-low': {
                    products.comparator = function(product) {
                        return product.get('pivot').sale_price * 100;
                    };
                    products.sort();
                    break;
                }

                case 'price-high': {
                    products.comparator = function(product) {
                        return product.get('pivot').sale_price * 100 * -1;
                    };
                    products.sort();
                    break;
                }

                case 'featured': {
                    products.comparator = function(product) {
                        return product.get('featured');
                    };
                    products.sort();
                    break;
                }
            }
        }
    });
});
