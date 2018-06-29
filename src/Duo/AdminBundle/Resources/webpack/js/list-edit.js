import $ from 'jquery';
import confirm from './util/confirm';

$(() =>
{
	$('.listing-edit').on('click', '[data-modal="confirm"]', async function(e)
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