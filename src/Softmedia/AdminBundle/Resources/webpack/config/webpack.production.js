const path = require('path');
const webpack = require('webpack');
const merge = require('webpack-merge');

const sassLoader = require('./sass-loader.config');
const config = require('./webpack.common');

const ExtractTextPlugin = require('extract-text-webpack-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const ManifestPlugin = require('webpack-manifest-plugin');

module.exports = merge(config, {
	module: {
		rules: [{
			test: /\.scss$/,
			use: ExtractTextPlugin.extract({
				fallback: 'style-loader',
				use: [{
					loader: 'css-loader',
					options: {
						minimize: true
					}
				}, 'resolve-url-loader', sassLoader]
			})
		}]
	},

	output: {
		filename: '[name].[hash].js'
	},

	plugins: [
		new webpack.DefinePlugin({
			'process.env': {
				'NODE_ENV': JSON.stringify('production')
			}
		}),

		new ExtractTextPlugin({
			filename: 'css/[name].[hash].css'
		}),

		new webpack.optimize.UglifyJsPlugin({
			compress: {
				warnings: false,
				screw_ie8: true,
				conditionals: true,
				unused: true,
				comparisons: true,
				sequences: true,
				dead_code: true,
				evaluate: true,
				join_vars: true,
				if_return: true
			},
			output: {
				comments: false
			}
		}),

		new ManifestPlugin({
			writeToFileEmit: true,
			basePath: '/backend/',
			map: (object) =>
			{
				if (/\.css$/.test(object.name))
				{
					object.name = `/backend/css/${object.chunk.name}.css`;
				}

				return object;
			}
		}),

		new CleanWebpackPlugin([
			'backend'
		], {
			verbose: true,
			exclude: ['.gitkeep'],
			root: path.resolve(process.cwd(), 'web/')
		})
	]
});