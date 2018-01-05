import $ from 'jquery';

const $window = $(window);

$(() =>
{
	const $nav = $('header > nav + nav');
	if (!$nav.length)
	{
		return;
	}

	const top = $nav.offset().top;
	const $main = $('main');

	$window.on('scroll.stickymenu', () =>
	{
		const isSticky = $window.scrollTop() > top;
		$nav.toggleClass('fixed-top', isSticky);

		$main.css('padding-top', isSticky ? $nav.outerHeight(true) : '');
	}).trigger('scroll.stickymenu');
});