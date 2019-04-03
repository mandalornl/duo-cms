let waitFor;

/**
 * Enable
 *
 * @param {Number} [delay]
 */
const enable = (delay = 0) =>
{
	window.clearTimeout(waitFor);
	waitFor = window.setTimeout(() =>
	{
		window.onbeforeunload = () =>
		{
			return true;
		};
	}, delay);
};

/**
 * Disable
 */
const disable = () =>
{
	window.clearTimeout(waitFor);
	window.onbeforeunload = null;
};

export default { enable, disable };
