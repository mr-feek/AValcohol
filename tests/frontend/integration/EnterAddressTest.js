/**
 * Created by Feek on 5/9/16.
 */
var utils = require('../utils.js');
var navigation = require('./navigation.js');

exports.runTest = function(test) {
	casper.AVcapture('enter_address_test_1');
	casper.sendKeys('input.street-address', '810 Walnut Street, State College, PA 16801', {keepFocus: true});
	casper.AVcapture('enter_address_test_2');
	casper.waitUntilVisible('.pac-container', function() {
		casper.AVcapture('enter_address_test_3');
		casper.click('.pac-container>.pac-item:nth-child(1)');
		casper.AVcapture('enter_address_test_4');
		casper.click('.submit-address');
		casper.AVcapture('enter_address_test_5');
		test.assertUrlMatch(this.getCurrentUrl(), navigation.home);
	});
};