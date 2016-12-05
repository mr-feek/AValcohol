/**
 * Created by Feek on 12/4/16.
 */
define([
    'marionette',
    'util/Brain',
    'App',
    'tpl!templates/user-home/product-comparator.html'
], function (
    Mn,
    Brain,
    app,
    tpl
) {
    return Mn.ItemView.extend({
        template: tpl,

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
            app.router.navigate('home?sort=' + value, {trigger: true});
        }
    });
});
