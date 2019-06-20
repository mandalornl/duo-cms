/**
 * Generate unique id
 *
 * @returns {String}
 */
export default () => Math.random().toString(36).substr(2, 10);
