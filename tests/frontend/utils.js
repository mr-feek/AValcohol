/**
 * Created by Feek on 5/9/16.
 */

/**
 * wrapper for storing our images in the correct directory
 * @param fileName
 * @constructor
 */
casper.numberOfImagesCaptured = 0;
casper.AVcapture = function(imageName) {
	this.numberOfImagesCaptured++;
	var imageDirectory = './tests/frontend/integration/images/';
	var imageUrl = imageDirectory + imageName + '_' + this.numberOfImagesCaptured + '.png';
	casper.capture(imageUrl);
};