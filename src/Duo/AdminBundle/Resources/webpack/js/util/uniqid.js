/**
 * Generate unique id
 *
 * @returns {string}
 */
export default () =>
{
	return Math.random().toString(36).substr(2, 10);
};