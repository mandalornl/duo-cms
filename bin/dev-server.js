#!/usr/bin/env node

require('dotenv').config();

const childProcess = require('child_process');
const path = require('path');

const chokidar = require('chokidar');

const WebpackDevServer = require('webpack-dev-server');

let webpackServer = null;

const PHP_HOST = process.env.PHP_HOST || '127.0.0.1';
const PHP_PORT = process.env.PHP_PORT || 3000;
const WEBPACK_HOST = process.env.WEBPACK_HOST || '0.0.0.0';
const WEBPACK_PORT = process.env.WEBPACK_PORT || 3030;

const phpServer = childProcess.spawn(`${process.cwd()}/bin/console`, [
	'server:run',
	`${PHP_HOST}:${PHP_PORT}`
], {
	stdio: [ 'ignore', 'inherit', 'inherit' ],
	env: Object.assign({}, process.env, {
		PATH: `${process.env.PATH}:${process.cwd()}/node_modules/bin`
	})
});

phpServer.on('close', code =>
{
	if (code !== 0)
	{
		console.error('Symfony console \'server:run\' failed');
	}

	if (webpackServer)
	{
		webpackServer.close();
	}
});

const webpackCfg = require(path.resolve(process.cwd(), 'webpack.config.js'));

Object.keys(webpackCfg.entry).forEach(entry =>
{
	webpackCfg.entry[entry].unshift(
		`webpack-dev-server/client?http://${WEBPACK_HOST}:${WEBPACK_PORT}/`,
		'webpack/hot/dev-server'
	);
});

const compiler = require('webpack')(webpackCfg);

const options = {
	host: WEBPACK_HOST,
	port: WEBPACK_PORT,
	proxy: {
		'/': {
			target: `http://${PHP_HOST}:${PHP_PORT}`
		}
	},
	compress: true,
	hot: true,
	disableHostCheck: true,
	inline: true,
	stats: {
		colors: true
	},
	publicPath: webpackCfg.output.publicPath,

	before: app =>
	{
		const routes = Object.keys(webpackCfg.entry).map(entry =>
		{
			return path.resolve(webpackCfg.output.publicPath, `css/${entry}.css`);
		});

		app.get(routes, (req, res) =>
		{
			res.setHeader('content-type', 'text/css');
			res.end('');
		});
	}
};

webpackServer = new WebpackDevServer(compiler, options);
webpackServer.listen(WEBPACK_PORT, err =>
{
	if (err)
	{
		console.error(err);

		if (phpServer)
		{
			phpServer.kill('SIGINT');
		}

		return;
	}

	console.info(`Webpack dev server running on http://${WEBPACK_HOST}:${WEBPACK_PORT}`);
});

/**
 * On change
 *
 * @param {String} filename
 */
const onChange = filename =>
{
	console.info(`Template '${filename}' changed`);

	webpackServer.sockWrite(webpackServer.sockets, 'content-changed');
};

const watcher = chokidar.watch([
	path.resolve(process.cwd(), 'app/Resources/**/*.twig'),
	path.resolve(process.cwd(), 'src/**/*.twig')
], {
	persistent: true,
	ignoreInitial: true
});

watcher.on('add', onChange);
watcher.on('change', onChange);
watcher.on('unlink', onChange);
