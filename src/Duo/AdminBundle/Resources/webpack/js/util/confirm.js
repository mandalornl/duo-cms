import $ from 'jquery';
import 'bootstrap/js/dist/modal';

/**
 * Confirm dialog
 *
 * @param {{}} [options]
 *
 * @returns {Promise<any>}
 */
export default (options = {}) => new Promise(resolve =>
{
	options = $.extend({}, {
		selector: '#modal_confirm',
		title: null,
		body: null
	}, options);

	const $modal = $(options.selector);

	if (options.title)
	{
		$modal.find('.modal-title').text(options.title);
	}

	if (options.body)
	{
		$modal.find('.modal-body').html(options.body.replace(/\r|\n|\r|\n/g, '<br>'));
	}

	const selector = 'button:not([data-dismiss])';

	$modal.off('click', selector).on('click', selector, () =>
	{
		$modal.modal('hide');

		resolve();
	}).modal('show');
});