/**
 * Generate uniqid
 *
 * @returns {string}
 */
const uniqid = () =>
{
	return Math.random().toString(36).substr(2, 10);
};

export default uniqid;