var gulp = require('gulp');
var ftp = require('vinyl-ftp');
var gutil = require('gulp-util');
var minimist = require('minimist');
var args = minimist(process.argv.slice(2));

gulp.task('deploy', function() {
	var conn = ftp.create({
		host: 'avalcohol.com',
		user: args.user,
		password: args.password,
		parallel: 7,
		log: gutil.log
	});

	// globs to remove on remote server
	var globs = [
		'api/**',
		'app/**',
		'bootstrap/**',
		'database/**',
		'public/**',
		'resources/**',
		'storage/**',
	];

	/**
	 * This will clean the out the remote server!
	 */
	var index;
	var i = 0;

	for (index = 0; index < globs.length; index++) {
		var glob = globs[index];
		glob = glob.slice(0, -3); // remove /**

		conn.rmdir('/public_html/dev/' + glob, function () {
			i++;
			if (i == globs.length) {
				// by now, all the directories we want to remove have been removed. time to upload!

				// upload EVERYTHING newer except stupid stuff
				globs = [
					'*',
					'*/**',
					'!node_modules/**',
					'!.env',
					'!.sass-cache/**',
					'!vendor/symfony/finder/Tests/**' // causing problems..
				]

				return gulp.src( globs, { base: '.', buffer: false, dot: true } )
					.pipe( conn.newer( '/public_html/dev' ) ) // only upload newer files
					.pipe( conn.dest( '/public_html/dev' ) );
			}
		});
	}


});