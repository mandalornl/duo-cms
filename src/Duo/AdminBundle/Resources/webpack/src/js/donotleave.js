let waitFor;

/**
 * Enable
 *
 * @param {Number} [delay = 1000]
 */
const enable = (delay = 1000) =>
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

export {enable, disable};