import $ from 'jquery';

($ =>
{
	const NAME = 'collection';
	const SELECTOR = `.widget-${NAME}`;

	/**
	 * Add item
	 *
	 * @param {Event} event
	 */
	const addItem = function(event)
	{
		event.preventDefault();

		const $this = $(this);

		const $container = $this.closest(SELECTOR);
		const $list = $container.find('> [data-prototype]');

		$list.data('index', $list.data('index') || $list.find('> .collection-item').length);

		const prototypeName = $list.data('prototype-name') || '__name__';

		let prototype = $list.data('prototype');
		prototype = prototype.split(`${prototypeName}label__`).join($list.data('index'));
		prototype = prototype.split(prototypeName).join($list.data('index'));

		const $item = $($container.data('item')).prepend(prototype);

		$list.data('index', $list.data('index') + 1);
		$list.append($item.addClass('fade'));

		// dispatch event
		$item.trigger('duo.event.collection.addItem');

		// toggle button
		$container.find('> button[data-action="add"]:last').toggleClass('d-none', !$list.find('> .collection-item').length);

		$('html, body').scrollTop($item.offset().top - 75);

		window.setTimeout(() =>
		{
			$item.addClass('show').on('bsTransitionEnd', () =>
			{
				$item.find(':input:visible').filter(function()
				{
					return this.value === '';
				}).first().focus();
			}).emulateTransitionEnd(150);
		}, 250);
	};

	/**
	 * Remove item
	 *
	 * @param {Event} event
	 */
	const removeItem = function(event)
	{
		event.preventDefault();

		const $this = $(this);

		const $container = $this.closest(SELECTOR);
		const $list = $container.find('> [data-prototype]');

		const $item = $this.closest(`.${$this.data('dismiss')}`);
		$item.addClass('fade show');

		window.setTimeout(() =>
		{
			$item.removeClass('show').on('bsTransitionEnd', () =>
			{
				// dispatch event
				$item.trigger('duo.event.collection.removeItem');
				$item.remove();

				// toggle button
				$container.find('> button[data-action="add"]:last').toggleClass('d-none', !$list.find('> .collection-item').length);
			}).emulateTransitionEnd(150);
		}, 0);
	};

	$(document)
		.on(`click.${NAME}.add`, `${SELECTOR} button[data-action="add"]`, addItem)
		.on(`click.${NAME}.remove`, `${SELECTOR} button[data-dismiss="collection-item"]`, removeItem);
})($);
