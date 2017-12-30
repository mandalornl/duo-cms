import $ from 'jquery';
import 'bootstrap/js/dist/util';
import 'bootstrap/js/dist/collapse';
import 'bootstrap/js/dist/dropdown';

import './sortable';

$(() =>
{
	const $list = $('.table-list');

	$list.on('click', 'tr[data-url] td:not(:first-child):not(:last-child)', function()
	{
		location.href = $(this).closest('tr').data('url');
	});

	$list.on('click', '[data-modal="delete"]', function(e)
	{
		e.preventDefault();

		const $this = $(this);
		const $modal = $('#modal_confirm_delete');

		$modal.on('show.bs.modal', () =>
		{
			$modal.on('click', '.btn:not([data-dismiss])', () =>
			{
				location.href = $this.attr('href');
			});
		}).modal('show');
	});
});