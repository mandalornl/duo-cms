import $ from 'jquery';
import 'bootstrap/js/dist/util';
import 'bootstrap/js/dist/collapse';
import 'bootstrap/js/dist/dropdown';
import 'bootstrap/js/dist/alert';

import {confirm} from "./util/modal";

$(() =>
{
	const $form = $('.form-list');
	if (!$form.length)
	{
		return;
	}

	const $list = $form.find('.table-list');

	$list.on('click', 'tr[data-url] td:not(:first-child):not(:last-child)', function()
	{
		location.href = $(this).closest('tr').data('url');
	});

	$list.on('click', '[data-modal="delete"]', confirm(function()
	{
		location.href = $(this).attr('href');
	}, '#modal_confirm_delete'));

	$('.listing-index').on('click', '[data-modal="multi-delete"]', confirm(function()
	{
		const $this = $(this);
		$this.prop('disabled', true);

		$form.attr('action', $this.attr('href')).submit();
	}));
});