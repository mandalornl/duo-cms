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
			// add title
			if ($this.data('title'))
			{
				$modal.find('.modal-title').text($this.data('title'));
			}

			// add body
			if ($this.data('body'))
			{
				$modal.find('.modal-body p').html($this.data('body').replace(/\r\n|\r|\n/g, '<br>'));
			}

			$modal.on('click', '.btn:not([data-dismiss])', () =>
			{
				callback.call(this);
			});
		}).modal('show');
	};
};

export {confirm};