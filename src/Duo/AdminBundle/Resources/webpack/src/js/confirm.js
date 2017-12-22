import $ from 'jquery';
import 'bootstrap/js/dist/modal';

/**
 * Open confirm dialog
 *
 * @param {Function} callback
 * @param {string} [selector]
 */
const open = (callback, selector = '#modal_confirm') =>
{
	const $modal = $(selector);

	$modal.on('show.bs.modal', function(e)
	{
		const $target = $(e.relatedTarget);

		$modal.find('.modal-title').text($target.data('title'));
		$modal.find('.modal-body p').html($target.data('body').replace(/\r\n|\r|\n/g, '<br>'));

		$modal.on('click', '.btn:not([data-dismiss])', function(e)
		{
			e.preventDefault();

			callback();
		});
	}).modal('show');
};

/**
 * Hide confirm dialog
 *
 * @param {string} [selector]
 */
const close = (selector = '#modal_confirm') =>
{
	$(selector).modal('hide');
};

export {open, close};