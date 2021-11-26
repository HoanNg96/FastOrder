define([
    'jquery',
    'uiComponent',
    'ko',
    'mage/storage',
    'mage/url'
], function ($, Component, ko, storage, urlBuilder) {
    'use strict';
    //extend parent
    return Component.extend({
        // if not declare template in phtml file
        defaults: {
            template: 'AHT_FastOrder/fastorder'
        },
        initialize: function () {
            // call parent initialize code
            this._super();
            // search binding
            this.searchInput = ko.observable('');
            this.searchResult = ko.observableArray(false);
            /* this.fullPrice = ko.observable(''); */
        },

        viewmodel: function () {
            var self = this;
            // build url
            var url = urlBuilder.build('fastorder/index/search');
            // ajax post
            var result = storage.post(
                url,
                JSON.stringify({ 'search': self.searchInput() }),
                false
            ).done(
                function (response) {
                    // response received from controller
                    console.log(Object.values(response.data))
                    self.searchResult(Object.values(response.data));
                }
            ).fail(
                function (response) {
                    console.log('Response Error');
                }
            );
        }

    });
}
);