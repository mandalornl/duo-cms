import $ from 'jquery';
import 'bootstrap/js/dist/util';
import 'bootstrap/js/dist/collapse';
import 'bootstrap/js/dist/dropdown';
import 'bootstrap/js/dist/alert';

import confirm from 'duo/AdminBundle/Resources/webpack/js/util/confirm';
import datePicker from 'duo/AdminBundle/Resources/webpack/js/lib/datepicker';
import select2 from 'duo/AdminBundle/Resources/webpack/js/lib/select2';
import postMessage from 'duo/AdminBundle/Resources/webpack/js/lib/post-message';

postMessage.on(window, (e) =>
{
	if (e.origin !== location.origin)
	{
		throw 'Not allowed';
	}

	try
	{
		const response = typeof e.data === 'string' ? JSON.parse(e.data) : e.data;

		if (response.event)
		{
			$(window).trigger(response.event, response.data);
		}
	}
	catch (err)
	{
		console.error(err);
	}
});

$(() =>
{
	const $form = $('.listing-index-form');

	if (!$form.length)
	{
		return;
	}

	$form.on('click', '[data-modal="delete"]', async function(e)
	{
		e.preventDefault();

		const $this = $(this);

		await confirm({
			selector: '#modal_confirm_delete',
			title: $this.data('title'),
			body: $this.data('body')
		});

		location.href = $this.attr('href');
	});

	$form.on('click', 'tr[data-url]', function(e)
	{
		if ($(e.target).closest(':input, a, .custom-control').length)
		{
			return;
		}

		location.href = $(this).closest('tr').data('url');
	});

	// handle iframe item selection
	$form.on('click', 'input[data-item], tr[data-item]', function(e)
	{
		e.preventDefault();

		postMessage.send(window.parent, {
			event: 'duo.event.iframe.select',
			data: $(this).data('item')
		}, location.origin);
	});

	$('.listing-index').on('click', '[data-modal="multi-delete"]', async function(e)
	{
		e.preventDefault();

		const $this = $(this);

		await confirm({
			title: $this.data('title'),
			body: $this.data('body')
		});

		$form.attr('action', $this.attr('href')).submit();
	});

	datePicker();
	select2();
});