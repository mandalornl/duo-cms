#!/usr/bin/env node

const fs = require('fs');

const glob = require('glob');
const _ = require('lodash');
const yargs = require('yargs')
	.usage('$0 [args]')
	.options({
		src: {
			type: 'string',
			describe: 'Directory where \'selection.json\' file(s) are located',
			demandOption: true,
			requiresArg: true
		},

		dest: {
			type: 'string',
			describe: 'Destination of scss file',
			demandOption: true,
			requiresArg: true
		}
	})
	.help()
	.argv;

const output = [];

glob(`${yargs.src}/**/selection.json`, (err, files) =>
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

	fs.writeFile(yargs.dest, output.join('\n'), err =>
	{
		if (err)
		{
			throw err;
		}

		console.log('The file has been saved.');
	});
});