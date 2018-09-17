import $ from 'jquery';

($ =>
{
	const NAME = 'collection';
	const SELECTOR = `.${NAME}-widget`;

	/**
	 * Add item
	 *
	 * @param {Event} e
	 */
	const addItem = function(e)
	{
		e.preventDefault();

		const $this = $(this);

		const $container = $this.closest(SELECTOR);
		const $list = $container.find('> [data-prototype]');

		$list.data('index', $list.data('index') || $list.find('> .collection-item').length);

		let prototype = $list.data('prototype');
		prototype = prototype.replace(/__name__label__/g, $list.data('index'));
		prototype = prototype.replace(/__name__/g, $list.data('index'));

		const $item = $($container.data('item')).prepend(prototype);

		$list.data('index', $list.data('index') + 1);
		$list.append($item.addClass('fade'));

		// dispatch event
		$item.trigger('duo.event.collection.addItem');

		// toggle button
		$container.find('button[data-action="add"]:last').parent().toggleClass('d-none', !$list.find('> .collection-item').length);

		$('html, body').scrollTop($item.offset().top);

		window.setTimeout(() => $item.addClass('show'), 250);
	};

	/**
	 * Remove item
	 *
	 * @param {Event} e
	 */
	const removeItem = function(e)
	{
		e.preventDefault();

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
				$container.find('button[data-action="add"]:last').parent().toggleClass('d-none', !$list.find('> .collection-item').length);
			}).emulateTransitionEnd(150);
		}, 0);
	};

	$(document)
		.on(`click.${NAME}.add`, `${SELECTOR} [data-action="add"]`, addItem)
		.on(`click.${NAME}.remove`, `${SELECTOR} [data-dismiss="collection-item"]`, removeItem)
	;
})($);