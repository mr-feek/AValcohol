/**
 * Created by Feek on 5/9/16.
 */
var enterAddress = require('./integration/EnterAddressTest.js');
var addProducts = require('./integration/AddProductsToCartTest.js');
var navigation = require('./integration/navigation');

var numTests = enterAddress.numTests;// + addProducts.numTests;

casper.test.begin('Testing Avalcohol', numTests, function(test) {
	casper.options.viewportSize = { width: 1280, height: 720 };

	casper.on('remote.message', function(msg) {
		this.echo('Browser: ' + msg);
	});

	//casper.options.verbose = true;
	casper.options.logLevel = "debug";
	casper.start(navigation.landing);

	casper.then(function() {
		enterAddress.runTest(test);
	});
	casper.then(function() {
	//	addProducts.runTest(test);
	});

	casper.run(function() {
		test.done();
	})
});