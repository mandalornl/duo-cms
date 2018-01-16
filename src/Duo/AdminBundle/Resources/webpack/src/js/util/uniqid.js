/**
 * Generate
 *
 * @returns {string}
 */
const generate = () =>
{
	return Math.random().toString(36).substr(2, 10);
};

export {generate};