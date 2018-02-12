import $ from 'jquery';
import 'bootstrap/js/dist/util';
import 'bootstrap/js/dist/collapse';
import 'bootstrap/js/dist/dropdown';
import 'bootstrap/js/dist/alert';

import {confirm} from "./util/modal";
import datePicker from './assets/datepicker';
import select2 from './assets/select2';

$(() =>
{
	const $form = $('.listing-form');
	if (!$form.length)
	{
		return;
	}

	const $list = $form.find('.listing-table');

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

	datePicker();
	select2();
});