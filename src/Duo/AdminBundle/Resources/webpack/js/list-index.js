import $ from 'jquery';
import 'bootstrap/js/dist/util';
import 'bootstrap/js/dist/collapse';
import 'bootstrap/js/dist/dropdown';
import 'bootstrap/js/dist/alert';

import 'duo/AdminBundle/Resources/webpack/js/lib/datepicker';
import 'duo/AdminBundle/Resources/webpack/js/lib/select2';
import confirm from 'duo/AdminBundle/Resources/webpack/js/util/confirm';
import postMessage from 'duo/AdminBundle/Resources/webpack/js/lib/post-message';

postMessage.on(window, (e) =>
{
	if (e.origin !== location.origin)
	{
		throw new Error('Not allowed');
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
	const $listing = $('.listing-index');

	if (!$listing.length)
	{
		return;
	}

	const $form = $listing.find('.listing-form');

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
			event: 'duo.event.iframe.selectItem',
			data: $(this).data('item')
		}, location.origin);
	});

	// handle multi delete
	$listing.on('click', '[data-modal="multi-delete"]', async function(e)
	{
		e.preventDefault();

		const $this = $(this);

		await confirm({
			title: $this.data('title'),
			body: $this.data('body')
		});

		$listing.find('.listing-form').attr('action', $this.attr('href')).submit();
	});

	// handle paginator limit
	$listing.on('change', '.paginator-limiter select', function(e)
	{
		e.preventDefault();

		location.href = this.value;
	});
});