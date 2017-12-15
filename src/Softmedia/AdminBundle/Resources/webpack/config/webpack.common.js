const path = require('path');
const webpack = require('webpack');

const rootFolder = process.cwd();

module.exports = {
	entry: {
		'admin.min': [
			'babel-polyfill',
			path.resolve(__dirname, '../src/admin.js')
		]
	},

	resolve: {
		alias: {
			'bundles': path.resolve(rootFolder, 'web/bundles')
		}
	},

	output: {
		filename: '[name].js',
		path: path.resolve(rootFolder, 'web/backend'),
		publicPath: '/backend/'
	},

	module: {
		rules: [{
			test: /\.js$/,
			exclude: /node_modules/,
			use: {
				loader: 'babel-loader',
				options: {
					presets: ['env'],
					plugins: ['transform-runtime', 'syntax-dynamic-import']
				}
			}
		}, {
			test: /\.(eot|ttf|otf|png|svg|jpe?g|gif)(\?[\s\S]+)?$/,
			use: 'file-loader'
		}, {
			test: /\.woff2?(\?v=[0-9]\.[0-9]\.[0-9])?$/,
			use: {
				loader: 'url-loader',
				options: {
					mimetype: 'application/font-woff',
					limit: 10000
				}
			}
		}, {
			test: /\.ejs$/,
			use: {
				loader: 'ejs-compiled-loader',
				options: {
					htmlmin: true,
					htmlminOptions: {
						removeComments: true,
						collapseWhitespace: true
					}
				}
			}
		}, {
			test: /\.html$/,
			use: {
				loader: 'html-loader',
				options: {
					minimize: true
				}
			}
		}]
	},

	plugins: [
		new webpack.ProvidePlugin({
			$: 'jquery',
			jQuery: 'jquery',

			Popper: ['popper.js', 'default'],

			Util: 'exports-loader?Util!bootstrap/js/dist/util',
			Collapse: 'exports-loader?Collapse!bootstrap/js/dist/collapse',
			Dropdown: 'exports-loader?Dropdown!bootstrap/js/dist/dropdown'
		})
	]
};