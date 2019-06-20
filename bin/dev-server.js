#!/usr/bin/env node

require('dotenv').config();

const childProcess = require('child_process');
const path = require('path');

const chokidar = require('chokidar');

const WebpackDevServer = require('webpack-dev-server');

let webpackServer = null;

const HOST = process.env.HOST || '127.0.0.1';
const PORT = process.env.PORT || 3000;
const PROXY_HOST = process.env.PROXY_HOST || '0.0.0.0';
const PROXY_PORT = process.env.PROXY_PORT || 3030;

const phpServer = childProcess.spawn(`${process.cwd()}/bin/console`, [
	'server:run',
	`${HOST}:${PORT}`
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
		`webpack-dev-server/client?http://${PROXY_HOST}:${PROXY_PORT}/`,
		'webpack/hot/dev-server'
	);
});

const compiler = require('webpack')(webpackCfg);

const options = {
	host: PROXY_HOST,
	port: PROXY_PORT,
	proxy: {
		'/': {
			target: `http://${HOST}:${PORT}`
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
			return path.resolve(webpackCfg.output.publicPath, `${entry}.css`);
		});

		app.get(routes, (req, res) =>
		{
			res.setHeader('content-type', 'text/css');
			res.end('');
		});
	}
};

webpackServer = new WebpackDevServer(compiler, options);
webpackServer.listen(PROXY_PORT, err =>
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

	console.info(`Webpack dev server running on http://${PROXY_HOST}:${PROXY_PORT}`);
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
