import $ from 'jquery';
import {parse} from 'querystring';

import * as loader from 'duo/AdminBundle/Resources/webpack/js/util/loader';
import confirm from 'duo/AdminBundle/Resources/webpack/js/util/confirm';
import postMessage from 'duo/AdminBundle/Resources/webpack/js/lib/post-message';

($ =>
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
			title: $this.data('title') || $this.text(),
			body: $this.data('body')
		});

		location.href = $this.attr('href');
	});

	$form.on('click', 'tr:not([data-item])', function(e)
	{
		if ($(e.target).closest(':input, a, .custom-control').length)
		{
			return;
		}

		const $anchor = $(this).closest('tr').find('.btn-edit');

		if (!$anchor.length)
		{
			return;
		}

		location.href = $anchor.attr('href');
	});

	// handle iframe item selection
	$form.on('click', 'input[data-item]:not(:disabled), tr[data-item]:not(.disabled)', function(e)
	{
		e.preventDefault();

		const params = parse(location.search.substr(1));

		postMessage.send(window.parent, {
			event: 'duo.event.iframe.selectItem',
			target: params.target || null,
			data: $(this).data('item')
		}, location.origin);
	});

	// handle actions
	$listing.on('click', '.navbar [data-modal="multi-action"]', async function(e)
	{
		e.preventDefault();

		const $this = $(this);

		await confirm({
			title: $this.data('title') || $this.text(),
			body: $this.data('body')
		});

		const target = $this.attr('target') || '_self';

		if (target === '_self')
		{
			loader.show();
		}

		$form.attr({
			action: $this.attr('href'),
			target: target
		}).submit();
	});

	// handle paginator limit
	$listing.on('change', '.paginator-limiter select', function(e)
	{
		e.preventDefault();

		location.href = this.value;
	});
})($);