import $ from 'jquery';

($ =>
{
	const $body = $(document.body);

	$(document).on('click.menu', '#menu', event =>
	{
		event.preventDefault();

		if (!$body.hasClass('sidebar-open'))
		{
			$body.addClass('sidebar-open sidebar-slide');

			return;
		}

		$body.removeClass('sidebar-slide').on('bsTransitionEnd', () =>
		{
			$body.removeClass('sidebar-open');
		}).emulateTransitionEnd(300);
	});
})($);
