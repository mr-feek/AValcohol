/**
 * Created by Feek on 5/9/16.
 */

/**
 * wrapper for storing our images in the correct directory
 * @param fileName
 * @constructor
 */
casper.AVcapture = function(imageName) {
	var imageDirectory = './tests/frontend/integration/images/';
	var imageUrl = imageDirectory + imageName + '.png';
	casper.capture(imageUrl);
};