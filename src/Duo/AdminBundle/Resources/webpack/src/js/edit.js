import $ from 'jquery';
import 'bootstrap/js/dist/modal';

//require('bootstrap-datepicker/dist/css/bootstrap-datepicker.css');
//require('bootstrap-datepicker/dist/js/bootstrap-datepicker');

/**
 * Confirm dialog
 *
 * @param {Event} e
 */
const confirmDialog = (e) =>
{
	e.preventDefault();

	const $this = $(e.target);
	const $modal = $('#modal_confirm');

	$modal.on('show.bs.modal', () =>
	{
		$modal.find('.modal-title').text($this.data('title'));
		$modal.find('.modal-body p').html($this.data('body').replace(/\r\n|\r|\n/g, '<br>'));

		$modal.on('click', '.btn:not([data-dismiss])', function(e)
		{
			e.preventDefault();

			location.href = $this.attr('href');
		});
	}).modal('show');
};

$(() =>
{
	const $document = $(document);

	$document.on('click', '[data-action]:not([data-action="save"])', confirmDialog);

	$document.on('click', )
});