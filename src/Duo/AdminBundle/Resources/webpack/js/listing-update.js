import $ from 'jquery';
import confirm from 'duo/AdminBundle/Resources/webpack/js/util/confirm';

$(() =>
{
	const $listing = $('.listing-create');

	if (!$listing.length)
	{
		return;
	}

	$listing.on('click', '[data-modal="confirm"]', async function(e)
	{
		e.preventDefault();

		const $this = $(this);

		await confirm({
			title: $this.data('title'),
			body: $this.data('body')
		});

		location.href = $this.attr('href');
	});
});