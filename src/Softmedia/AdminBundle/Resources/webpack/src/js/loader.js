import $ from 'jquery';

const $body = $(document.body);

/**
 * Show loader
 *
 * @param {boolean} [immediately = false]
 */
const show = (immediately = false) =>
{
	$body.addClass(`loading${immediately ? ' immediately' : ''}`);
};

/**
 * Hide loader
 */
const hide = () =>
{
	$body.removeClass('loading immediately');
};

export {show, hide};