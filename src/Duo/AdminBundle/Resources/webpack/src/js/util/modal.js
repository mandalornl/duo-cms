import $ from 'jquery';
import 'bootstrap/js/dist/modal';

/**
 * Open confirm dialog
 *
 * @param {Function} callback
 * @param {string} [selector]
 *
 * @returns {function(*=)}
 */
const confirm = (callback, selector = '#modal_confirm') =>
{
	return (e) =>
	{
		e.preventDefault();

		const $this = $(e.target);
		const $modal = $(selector);

		$modal.on('show.bs.modal', () =>
		{
			$modal.find('.modal-title').text($this.data('title'));
			$modal.find('.modal-body p').html($this.data('body').replace(/\r\n|\r|\n/g, '<br>'));

			$modal.on('click', '.btn:not([data-dismiss])', () =>
			{
				callback(e);
			});
		}).modal('show');
	};
};

export {confirm};