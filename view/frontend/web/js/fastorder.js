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
        // required if not declare template in phtml file
        defaults: {
            template: 'AHT_FastOrder/fastorder'
        },

        // for declare observables
        initialize: function () {
            // call parent initialize code
            this._super()
            // binding values
            this.searchInput = ko.observable()
            this.searchResult = ko.observableArray(false)
            this.isSelected = ko.observable(false).extend({ rateLimit: { timeout: 500 } })
            this.searchResultHover = ko.observable(false).extend({ rateLimit: { timeout: 500 } })
            this.cartItem = ko.observableArray(false)
            this.totalProduct = ko.observableArray(false);
            this.totalQty = ko.observableArray(false);
            this.cartSubTotal = ko.observable('0');

        },

        viewmodel: function () {
            var self = this
            self.searchResult(false)
            // build url
            var url = urlBuilder.build('fastorder/index/search')
            // ajax post
            var result = storage.post(
                url,
                JSON.stringify({ 'search': self.searchInput() }),
                false
            ).done(
                // response = json result received from controller
                // data = key of json result
                function (response) {
                    console.log(Object.values(response.data))
                    self.searchResult(Object.values(response.data))
                }
            ).fail(
                function (response) {
                    console.log(response)
                }
            );
        },

        enableSearchResult: function () {
            this.searchResultHover(true);
        },

        disableSearchResult: function () {
            this.searchResultHover(false);
        },

        // item = product corresponding to add cart button
        addToCart: function (item) {
            this.cartItem.push(item)
            console.log(this.cartItem)
        }
    });
}
);