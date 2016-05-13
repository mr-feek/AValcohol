/**
 * Created by Feek on 5/9/16.
 */
var utils = require('../utils.js');
var navigation = require('./navigation.js');

exports.numTests = 1;

exports.runTest = function(test) {
	casper.wait(5000);
	casper.echo('testing add products to cart');
	//casper.waitForSelector('#products .product'); // wait for products to load
	casper.then(function() {
		casper.AVcapture('add_products_to_cart_1');
	});
	casper.then(function() {
		casper.click('.product:nth-child(1) .button');
		casper.AVcapture('add_products_to_cart_2');
	});
};