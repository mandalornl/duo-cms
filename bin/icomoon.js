#!/usr/bin/env node

const fs = require('fs');
const path = require('path');

const glob = require('glob');
const minimist = require('minimist');
const _ = require('lodash');

const args = _.extend({
	root: path.resolve(process.cwd(), 'src/Duo/AdminBundle/Resources/webpack/src')
}, minimist(process.argv.slice(2)));

const source = path.resolve(args.root, 'fonts');
const destination = path.resolve(args.root, 'sass/_icomoon.scss');

const output = [];

glob(`${source}/**/selection.json`, (err, files) =>
{
	if (err)
	{
		throw err;
	}

	_.each(files, filename =>
	{
		try
		{
			const data = JSON.parse(fs.readFileSync(filename));
			const prefix = data.preferences.fontPref.prefix;

			_.each(data.icons, icon =>
			{
				const name = icon.properties.name;
				const code = (icon.properties.code).toString(16);
				const line = `$${prefix}${name}: "\\${code}";`;

				output.push(line);

				console.log(`'${line}' added`);
			});
		}
		catch (err)
		{
			console.error(err);
		}
	});

	fs.writeFile(destination, output.join('\n'), err =>
	{
		if (err)
		{
			throw err;
		}

		console.log('The file has been saved.');
	});
});