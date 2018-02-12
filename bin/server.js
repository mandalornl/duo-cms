#!/usr/bin/env node

const childProcess = require('child_process');
const path = require('path');

const _ = require('lodash');
const chokidar = require('chokidar');
const readYmlSync = require('read-yaml').sync;

const bundleRoot = path.resolve(process.cwd(), 'src/Duo/AdminBundle');

const WebpackDevServer = require('webpack-dev-server');

let webpackDevServer = null;

const root = path.dirname(__dirname);
const env = _.extend({}, process.env, {
	PATH: `${process.env.PATH}:${root}/node_modules/.bin`
});

const parameters = (readYmlSync(`${process.cwd()}/app/config/parameters.yml`, null)).parameters;

const phpServer = childProcess.spawn(`${root}/bin/console`, [
	'server:run',
	`${parameters['php_dev_host']}:${parameters['php_dev_port']}`
], {
	stdio: ['ignore', 'inherit', 'inherit'],
	env: env
});

phpServer.on('close', (code) =>
{
	if (code !== 0)
	{
		console.error('Symfony console server:run failed');
	}

	if (webpackDevServer)
	{
		webpackDevServer.close();
	}
});

const config = require(`${bundleRoot}/Resources/webpack/webpack.config`);
const compiler = require('webpack')(config);

const options = _.merge({}, config.devServer, {
	publicPath: config.output.publicPath
});

config.entry['admin.min'].unshift(
	`webpack-dev-server/client?http://${parameters['webpack_dev_host']}:${parameters['webpack_dev_port']}`,
	'webpack/hot/dev-server'
);

webpackDevServer = new WebpackDevServer(compiler, options);
webpackDevServer.listen(parameters['webpack_dev_port'], (err) =>
{
	if (err)
	{
		return phpServer.kill('SIGINT');
	}

	console.info(`Webpack dev server running on http://${parameters['webpack_dev_host']}:${parameters['webpack_dev_port']}/`);
});

/**
 * On change
 *
 * @param {string} path
 */
const onChange = (path) =>
{
	console.info(`Template ${path} changed`);

	webpackDevServer.sockWrite(webpackDevServer.sockets, 'content-changed');
};

const watcher = chokidar.watch(`${bundleRoot}/Resources/views`, {
	persistent: true,
	ignoreInitial: true
});

watcher.on('add', onChange);
watcher.on('change', onChange);
watcher.on('unlink', onChange);