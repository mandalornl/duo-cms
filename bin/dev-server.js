#!/usr/bin/env node

const path = require('path');
const chokidar = require('chokidar');
const WebpackDevServer = require('webpack-dev-server');

const host = process.env.HOST || '0.0.0.0';
const port = process.env.PORT || 8080;

const webpackCfg = require(path.resolve(process.cwd(), 'webpack.config.js'));

Object.keys(webpackCfg.entry).forEach(entry => {
  webpackCfg.entry[entry].unshift(
    `webpack-dev-server/client?http://${host}:${port}/`,
    'webpack/hot/dev-server'
  );
});

const compiler = require('webpack')(webpackCfg);

const options = {
  host: host,
  port: port,
  proxy: {
    '/': {
      target: `http://127.0.0.1:8000`
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

  before: app => {
    const routes = Object.keys(webpackCfg.entry).map(entry => {
      return path.resolve(webpackCfg.output.publicPath, `${entry}.css`);
    });

    app.get(routes, (req, res) => {
      res.setHeader('content-type', 'text/css');
      res.end('');
    });
  }
};

const webpackServer = new WebpackDevServer(compiler, options);
webpackServer.listen(port, err => {
  if (err) {
    console.error(err);

    return;
  }

  console.info(`Webpack dev server running on http://${host}:${port}`);
});

/**
 * On change
 *
 * @param {String} filename
 */
const onChange = filename => {
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
