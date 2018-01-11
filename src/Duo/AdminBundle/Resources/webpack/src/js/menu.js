import $ from 'jquery';

$(() =>
{
	const $body = $(document.body);

	$(document).on('click', '#menu', function(e)
	{
		e.preventDefault();

		if (!$body.hasClass('sidebar-open'))
		{
			$body.addClass('sidebar-open sidebar-slide');
		}
		else
		{
			$body.removeClass('sidebar-slide').on('bsTransitionEnd', function()
			{
				$body.removeClass('sidebar-open');
			}).emulateTransitionEnd(300);
		}
	});
});