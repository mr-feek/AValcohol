/**
 * Created by Feek on 5/9/16.
 */
var utils = require('../utils.js');
var navigation = require('./navigation.js');
var chai = require('chai');

exports.numTests = 1;
var placeSelector = '.pac-container .pac-item:first-child';

exports.runTest = function(test) {
	casper.echo('testing entering address');
	casper.waitForSelector('input.street-address'); // wait for page to load
	casper.AVcapture('enter_address_test_1');
	casper.sendKeys('input.street-address', '810 Walnut Street, State College', {keepFocus: true});

	casper.waitUntilVisible(placeSelector, function() {
		casper.AVcapture('enter_address_test_2');
	});

	casper.then(function() {
			//casper.click(placeSelector); // RIGHT NOW THIS DOES NOT SEEM TO REALLY BE WORKING
		casper.evaluate(function() {
			google.maps.places.Autocomplete.event.trigger(placeSelector, 'click');
		});
			//casper.sendKeys('input.street-address', '', {keepFocus: true});
			//casper.page.sendEvent('keypress', casper.page.event.key.down);
			//casper.AVcapture('enter_address_test_3');
			//casper.captureSelector('test.png', '.pac-container .pac-item:nth-child(1)');
	});

	casper.then(function() {
		//casper.waitWhileVisible('.pac-container');
	});

	casper.then(function() {
		casper.AVcapture('enter_address_test_4');
	});

	var formVal;
	casper.then(function() {
		formVal = casper.evaluate(function () {
			return $('input.street-address').val();
		});
	});

	casper.then(function() {
		casper.captureSelector('test.png', 'input.street-address');
		chai.assert.equal(formVal, '810 Walnut Street, State College, PA, United States');
		casper.AVcapture('enter_address_test_2');
	});

	casper.then(function() {
		casper.click('.submit-address');
	});

	casper.then(function() {
		casper.AVcapture('enter_address_test_5');
	});

	casper.then(function() {
		var url = casper.getCurrentUrl();
		chai.assert.equal(url, navigation.home);
	});

	casper.then(function() {
		casper.AVcapture('enter_address_test_6');
	});
};