const webpack = require('webpack');
const merge = require('webpack-merge');

const sassLoader = require('./sass-loader.config');
const config = require('./webpack.common');

const WebpackNotifierPlugin = require('webpack-notifier');

module.exports = merge(config, {
	devServer: {
		port: 8080,
		host: '0.0.0.0',
		proxy: {
			'/': {
				target: 'http://127.0.0.1:8000'
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