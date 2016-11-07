/**
 * Created by Feek on 10/30/16.
 */

var path = require('path');
var BowerWebpackPlugin = require('bower-webpack-plugin');

module.exports = {
	node: {
		fs: 'empty'
	},
	entry: {
		main: ['./default/js/main.js']
	},
	output: {
		path: path.join(__dirname, 'dist'),
		filename: '[name].entry.js'
	},
	resolve: {
		alias: {
			behaviors: path.resolve('./vendor/UsefulMarionetteViewBehaviors'),
			foundation: path.resolve('./vendor/foundation-sites/js/foundation.core'),
			foundationEqualizer: path.resolve('./vendor/foundation-sites/js/foundation.equalizer'),
			foundationTooltip: path.resolve('./vendor/foundation-sites/js/foundation.tooltip'),
			foundationOffCanvas: path.resolve('./vendor/foundation-sites/js/foundation.offcanvas'),
			nprogress: path.resolve('./vendor/nprogress/nprogress.js'),
			backboneRelational: 'backbone-relational'
		},
		modulesDirectories: [
			'public',
			'js'
		],
		extensions: ['', '.config.js', '.js', '.html']
	},

	module:  {
		loaders: [
			{
				test: /\.html$/,
				loader: 'underscore-template-loader'
			}
		]
	},
	plugins: [
		new BowerWebpackPlugin({
			modulesDirectories: ['./vendor', '../node_modules']
		})
	]
};