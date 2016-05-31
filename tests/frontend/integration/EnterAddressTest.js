/**
 * Created by Feek on 5/9/16.
 */
var utils = require('../utils.js');
var navigation = require('./navigation.js');
var chai = require('chai');

exports.numTests = 0;

var placeSelector = '.pac-container .pac-item:first-child';
var inputSelector = 'input.street-address';
var buttonSelector = '.submit-address';

exports.runTest = function(test) {
	casper.echo('testing entering address');
	casper.waitForSelector(inputSelector);

	casper.then(function() {
		casper.sendKeys(inputSelector, '810 Walnut Street, State College', {keepFocus: true});
	})

	casper.wait(3000);

	casper.then(function() {
		casper.page.sendEvent('keypress', casper.page.event.key.Down);
		casper.AVcapture('enter_address_test');
		casper.page.sendEvent('keypress', casper.page.event.key.Enter);
		casper.AVcapture('enter_address_test');
	});

	casper.thenEvaluate(function() {
		$(inputSelector).blur();
	}, placeSelector, inputSelector);

	var formVal;
	casper.then(function() {
		formVal = casper.evaluate(function (inputSelector) {
			return $(inputSelector).val();
		}, inputSelector);
	});

	casper.then(function() {
		chai.assert.equal(formVal, '810 Walnut Street, State College, PA, United States');
		casper.click(buttonSelector);
		casper.wait(1000);
	});

	casper.then(function() {
		casper.AVcapture('enter_address_test');
	});

	casper.then(function() {
		var url = casper.getCurrentUrl();
		chai.assert.equal(url, navigation.home);
	});
};