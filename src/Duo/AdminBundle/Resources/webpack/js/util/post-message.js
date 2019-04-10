/**
 * Add event listener
 *
 * @param {Window} target
 * @param {Function} eventHandler
 */
const on = (target, eventHandler) =>
{
	if (!target.addEventListener)
	{
		target.attachEvent('onmessage', eventHandler);

		return;
	}

	target.addEventListener('message', eventHandler, false);
};

/**
 * Remove event listener
 *
 * @param {Window} target
 */
const off = target =>
{
	if (!target.removeEventListener)
	{
		target.detachEvent('onmessage');

		return;
	}

	target.removeEventListener('message');
};

/**
 * Send message
 *
 * @param {Window} target
 * @param {{}} data
 * @param {String} [domain = '*']
 */
const send = (target, data, domain = '*') =>
{
	target.postMessage(JSON.stringify(data), domain);
};

export default { on, off, send };
