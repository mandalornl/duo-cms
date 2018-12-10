const path = require('path');
const webpack = require('webpack');

const NotifierPlugin = require('webpack-notifier');
const CleanPlugin = require('clean-webpack-plugin');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const ManifestPlugin = require('webpack-manifest-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const env = process.env.NODE_ENV || 'development';

const sassLoader = {
	loader: 'sass-loader',
	options: {
		sourceMap: true
	}
};

module.exports = {
	mode: env,

	resolve: {
		alias: {
			'~': __dirname,
			'~~': __dirname,
			'@': __dirname,
			'@@': __dirname,
			duo: path.resolve(__dirname, 'src/Duo') // TODO: change to 'vendor/duo/duo-cms/src/Duo'
		}
	},

	output: {
		filename: env === 'production' ? '[name].[hash].js' :'[name].js',
		path: path.resolve(__dirname, 'web/build'),
		publicPath: '/build/'
	},

	entry: {
		'app.min': [
			'babel-polyfill',
			path.resolve(__dirname, 'webpack/app.js')
		],

		'admin.min': [
			'babel-polyfill',
			path.resolve(__dirname, 'webpack/admin.js')
		]
	},

	module: {
		rules: [{
			test: /\.css$/,
			loader: [
				'style-loader',
				'css-loader'
			]
		}, {
			test: /\.scss$/,
			use: env === 'production' ? [
				MiniCssExtractPlugin.loader,
				{
					loader: 'css-loader',
					options: {
						minimize: true
					}
				}, 'resolve-url-loader', sassLoader
			] : [
				'style-loader',
				'css-loader',
				'resolve-url-loader', sassLoader
			]
		}, {
			test: /\.js$/,
			exclude: /node_modules/,
			use: {
				loader: 'babel-loader',
				options: {
					presets: [ 'env' ],
					plugins: [ 'transform-runtime', 'syntax-dynamic-import' ]
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

	devtool: env === 'development' ? 'source-map': false,

	plugins: [
		new webpack.ProvidePlugin({
			$: 'jquery',
			jQuery: 'jquery',
			'window.jQuery': 'jquery',

			Popper: ['popper.js', 'default'],

			Util: 'exports-loader?Util!bootstrap/js/dist/util',
			Collapse: 'exports-loader?Collapse!bootstrap/js/dist/collapse',
			Dropdown: 'exports-loader?Dropdown!bootstrap/js/dist/dropdown',
			Alert: 'exports-loader?Alert!bootstrap/js/dist/alert'
		})
	].concat(env === 'production' ? [
		new webpack.DefinePlugin({
			'process.env': {
				'NODE_ENV': JSON.stringify('production')
			}
		}),

		new MiniCssExtractPlugin({
			filename: 'css/[name].[hash].css'
		}),

		new UglifyJsPlugin({
			uglifyOptions: {
				compress: {
					warnings: false
				},
				sourceMap: true,
				output: {
					comments: true
				}
			},
			parallel: true,
			cache: true
		}),

		new ManifestPlugin({
			writeToFileEmit: true,
			basePath: '/build/',
			filename: 'manifest.json',
			map: (file) =>
			{
				if (/\.css$/.test(file.name))
				{
					file.name = `/build/css/${file.chunk.name}.css`;
				}

				return file;
			}
		}),

		new CleanPlugin([
			'build'
		], {
			verbose: true,
			exclude: [ '.gitkeep' ],
			root: path.resolve(__dirname, 'web/')
		}),

		new webpack.NoEmitOnErrorsPlugin()
	] : [
		new webpack.HotModuleReplacementPlugin(),
		new webpack.NamedModulesPlugin(),
		new NotifierPlugin({
			skipFirstNotification: true
		})
	])
};
