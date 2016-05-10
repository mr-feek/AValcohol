/**
 * Created by Feek on 5/9/16.
 */
var enterAddress = require('./integration/EnterAddressTest.js');
var navigation = require('./integration/navigation');

var numTests = 1; // number of tests expected to be run

casper.test.begin('Testing Avalcohol', numTests, function(test) {
	casper.options.viewportSize = { width: 1280, height: 720 };
	casper.start(navigation.landing);

	casper.then(function() {
		this.echo('starting testing of avalcohol main');
		enterAddress.runTest(test);
	});

	casper.run(function() {
		test.done();
	})
});