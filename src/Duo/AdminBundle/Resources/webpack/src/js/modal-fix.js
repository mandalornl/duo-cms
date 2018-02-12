import $ from 'jquery';

$(() =>
{
	const $document = $(document);
	const $body = $(document.body);

	// fix for multiple modals
	$document.on('show.bs.modal', '.modal', function ()
	{
		const zIndex = 1040 + (10 * $('.modal:visible').length);

		$(this).css('z-index', zIndex);

		setTimeout(() =>
		{
			$('.modal-backdrop:not(.modal-stack)').css('z-index', zIndex - 1).addClass('modal-stack');
		}, 0);
	});

	$document.on('hidden.bs.modal', '.modal', () => setTimeout(() =>
	{
		$body.toggleClass('modal-open', $('.modal:visible').length !== 0)
	}, 0));
});