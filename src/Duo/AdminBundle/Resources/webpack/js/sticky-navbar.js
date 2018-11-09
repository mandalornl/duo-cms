import $ from 'jquery';

($ =>
{
	const $nav = $('header > nav + nav');

	if (!$nav.length)
	{
		return;
	}

	const NAME = 'sticky-navbar';

	const top = $nav.offset().top;
	const $body = $(document.body);
	const $main = $('main');
	const $parent = $nav.parent();

	const $window = $(window);

	$window.on(`scroll.${NAME}`, () =>
	{
		const isSticky = $window.scrollTop() > top;

		$main.css('padding-top', isSticky ? $nav.outerHeight(true) : '');
		$nav.toggleClass('fixed-top', isSticky);

		if ($window.width() < 768)
		{
			// move to body to account for translate3d of parent elements
			if (isSticky)
			{
				$body.prepend($nav);
				return;
			}

			$parent.append($nav);
		}

	}).trigger(`scroll.${NAME}`);
})($);