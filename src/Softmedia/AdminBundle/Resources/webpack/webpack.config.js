const path = require('path');
const fs = require('fs-extra');

const env = process.env.NODE_ENV || 'development';

const config = require(`./config/webpack.${env}`);

if (env === 'development')
{
	const filename = path.resolve(process.cwd(), 'web/backend/css/admin.min.css');

	// ensure dir exists
	fs.ensureDirSync(path.dirname(filename));

	// create empty file
	fs.writeFileSync(filename, '');
}

module.exports = config;