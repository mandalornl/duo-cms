import $ from 'jquery';
import 'bootstrap/js/dist/modal';

/**
 * Open confirm dialog
 *
 * @param {Function} callback
 * @param {string} [selector]
 *
 */
const confirm = (callback, selector = '#modal_confirm') =>
{
	return function(e)
	{
		e.preventDefault();

		const $this = $(this);
		const $modal = $(selector);

		$modal.on('show.bs.modal', () =>
		{
			$modal.find('.modal-title').text($this.data('title'));
			$modal.find('.modal-body p').html($this.data('body').replace(/\r\n|\r|\n/g, '<br>'));

			$modal.on('click', '.btn:not([data-dismiss])', () =>
			{
				callback.call(this);
			});
		}).modal('show');
	};
};

export {confirm};