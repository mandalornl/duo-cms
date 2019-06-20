import $ from 'jquery';

import dialog from 'Duo/AdminBundle/Resources/webpack/js/util/dialog';

$(() =>
{
	const $listing = $('.listing-create');

	if (!$listing.length)
	{
		return;
	}

	$listing.on('click', '[data-modal="dialog"]', async function(event)
	{
		event.preventDefault();

		const $this = $(this);

		await dialog({
			title: $this.data('title') || $this.attr('title') || $this.text(),
			body: $this.data('body')
		});

		location.href = $this.attr('href');
	});
});
