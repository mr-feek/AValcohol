/**
 * Created by Feek on 10/30/16.
 */

var path = require('path');
var webpack = require('webpack');

module.exports = {
	node: {
		fs: 'empty'
	},
	entry: {
		main: ['./default/js/main.js']
	},
	output: {
		path: path.join(__dirname, 'dist'),
		publicPath: 'dist/',
		filename: '[name].entry.js'
	},
	resolve: {
		alias: {
			backbone: 				path.resolve('./vendor/backbone/backbone'),
			'backbone.babysitter':	path.resolve('./vendor/backbone.babysitter/lib/backbone.babysitter'),
			'backbone.wreqr': 		path.resolve('./vendor/backbone.wreqr/lib/backbone.wreqr'),
			backboneRelational: 	'backbone-relational',
			behaviors:				path.resolve('./vendor/UsefulMarionetteViewBehaviors'),
			foundation:				path.resolve('./vendor/foundation-sites/js/foundation.core'),
			foundationEqualizer:	path.resolve('./vendor/foundation-sites/js/foundation.equalizer'),
			foundationTooltip: 		path.resolve('./vendor/foundation-sites/js/foundation.tooltip'),
			foundationOffCanvas: 	path.resolve('./vendor/foundation-sites/js/foundation.offcanvas'),
			jquery: 				path.resolve('./vendor/jquery/src/jquery'),
			moment:					path.resolve('./vendor/moment/moment'),
			nprogress:				path.resolve('./vendor/nprogress/nprogress.js'),
			underscore: 			path.resolve('./vendor/underscore/underscore')
		},
		modulesDirectories: [
			'public',
			'js',
			'vendor'
		],
		extensions: ['', '.config.js', '.js', '.html']
	},

	module:  {
		loaders: [
			{
				test: /\.html$/,
				loader: 'underscore-template-loader'
			},
			{
				test: /(foundation\.core)/,
				loader: 'exports?foundation=jQuery.fn.foundation'
			}
		]
	},

	plugins: [
		new webpack.ProvidePlugin({
			$: 'jquery',
			jQuery: 'jquery',
			'window.jQuery': 'jquery',
			foundation: 'Foundation'
		})
	]
};
