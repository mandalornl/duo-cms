const webpack = require('webpack');
const merge = require('webpack-merge');

const sassLoader = require('./sass-loader.config');
const config = require('./webpack.common');

const readYmlSync = require('read-yaml').sync;

const WebpackNotifierPlugin = require('webpack-notifier');

const parameters = (readYmlSync(`${process.cwd()}/app/config/parameters.yml`, null)).parameters;

module.exports = merge(config, {
	devServer: {
		host: parameters['webpack_dev_host'],
		port: parameters['webpack_dev_port'],
		proxy: {
			'/': {
				target: `http://${parameters['php_dev_host']}:${parameters['php_dev_port']}`
			}
		},
		compress: true,
		hot: true,
		disableHostCheck: true,
		inline: true,
		stats: {
			colors: true
		}
	},

	module: {
		rules: [{
			test: /\.scss$/,
			use: [
				'style-loader',
				'css-loader',
				'resolve-url-loader',
				sassLoader
			]
		}]
	},

	devtool: 'source-map',

	plugins: [
		new webpack.NoEmitOnErrorsPlugin(),
		new webpack.HotModuleReplacementPlugin(),
		new webpack.NamedModulesPlugin(),
		new WebpackNotifierPlugin({
			skipFirstNotification: true
		})
	]
});