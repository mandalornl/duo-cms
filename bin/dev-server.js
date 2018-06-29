#!/usr/bin/env node

const childProcess = require('child_process');
const path = require('path');
const fs = require('fs');

const chokidar = require('chokidar');

const WebpackDevServer = require('webpack-dev-server');

let webpackServer = null;

const env = Object.assign({}, process.env, {
	PATH: `${process.env.PATH}:${process.cwd()}/node_modules/bin`
});

/**
 * Read config
 *
 * @param {string} filename
 *
 * @returns {{}}
 */
const readConfig = filename =>
{
	try
	{
		return JSON.parse(fs.readFileSync(path.resolve(process.cwd(), filename)));
	}
	catch (err)
	{
		console.error(`Unable to read ${filename}`);

		return {};
	}
};

const config = Object.assign({
	php: {
		host: '127.0.0.1',
		port: 8000
	},
	webpack: {
		host: '0.0.0.0',
		port: 8080
	}
}, readConfig('webpack/config.json'));

const phpServer = childProcess.spawn(`${process.cwd()}/bin/console`, [
	'server:run',
	`${config.php.host}:${config.php.port}`
], {
	stdio: [ 'ignore', 'inherit', 'inherit' ],
	env: env
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
		`webpack-dev-server/client?http://${config.webpack.host}:${config.webpack.port}/`,
		'webpack/hot/dev-server'
	);
});

const compiler = require('webpack')(webpackCfg);

const options = {
	host: config.webpack.host,
	port: config.webpack.port,
	proxy: {
		'/': {
			target: `http://${config.php.host}:${config.php.port}`
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
webpackServer.listen(config.webpack.port, err =>
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

	console.info(`Webpack dev server running on http://${config.webpack.host}:${config.webpack.port}`);
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