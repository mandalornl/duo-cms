#!/usr/bin/env node

const childProcess = require('child_process');
const path = require('path');

const _ = require('lodash');
const chokidar = require('chokidar');
const readYaml = require('util').promisify(require('read-yaml'));

const bundleRoot = path.resolve(process.cwd(), 'src/Softmedia/AdminBundle');

const WebpackDevServer = require('webpack-dev-server');

(async () =>
{
	let webpackDevServer = null;

	const root = path.dirname(__dirname);
	const env = _.extend({}, process.env, {
		PATH: `${process.env.PATH}:${root}/node_modules/.bin`
	});

	const parameters = (await readYaml(`${process.cwd()}/app/config/parameters.yml`)).parameters;

	const phpServer = childProcess.spawn(`${root}/bin/console`, [
		'server:run'
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
		`webpack-dev-server/client?http://${parameters['webpack.devserver.host']}:8080`,
		'webpack/hot/dev-server'
	);

	webpackDevServer = new WebpackDevServer(compiler, options);
	webpackDevServer.listen(8080, (err) =>
	{
		if (err)
		{
			return phpServer.kill('SIGINT');
		}

		console.info(`Webpack dev server running on http://${parameters['webpack.devserver.host']}:8080/`);
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
})();