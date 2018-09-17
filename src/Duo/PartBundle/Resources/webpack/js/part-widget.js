import $ from 'jquery';

import sortable from 'duo/AdminBundle/Resources/webpack/js/lib/sortable';
import {get} from 'duo/AdminBundle/Resources/webpack/js/util/api';

export default ($ =>
{
	const NAME = 'part';
	const SELECTOR = `.${NAME}-widget`;

	const modals = {};

	/**
	 * Get jQuery
	 *
	 * @param {string|HTMLElement|jQuery} selector
	 *
	 * @returns {jQuery}
	 */
	const _$ = selector => (selector instanceof jQuery || 'jquery' in Object(selector)) ? selector : $(selector);

	const methods = {

		SELECTOR: SELECTOR,

		/**
		 * Initialize
		 *
		 * @param {string|HTMLElement|jQuery} selector
		 */
		init: selector =>
		{
			_$(selector).each(function()
			{
				const $this = $(this);

				if ($this.data(`init.${NAME}`))
				{
					return;
				}

				$this.data(`init.${NAME}`, true);

				const $list = $this.find('.sortable-list');

				$list.data('index', $list.data('index') || $list.find('> .sortable-item').length);

				/**
				 * Update weight
				 */
				const updateWeight = () =>
				{
					$list.find('.sortable-item [name$="[weight]"]').each(function(weight)
					{
						$(this).val(weight);
					});
				};

				/**
				 * Toggle button
				 */
				const toggleButton = () =>
				{
					$this.find('button[data-url][data-prototype-id]:last').parent().toggleClass('d-none', !$list.find('> .sortable-item').length);
				};

				$list.on(`click.${NAME}`, '[data-dismiss="sortable-item"]', function(e)
				{
					e.preventDefault();

					const $this = $(this);

					const $item = $this.closest(`.${$this.data('dismiss')}`);
					$item.addClass('fade show');

					window.setTimeout(() =>
					{
						$item.removeClass('show').on('bsTransitionEnd', () =>
						{
							// dispatch event
							$item.trigger('duo.event.part.removeItem');
							$item.remove();

							updateWeight();
							toggleButton();
						}).emulateTransitionEnd(150);
					}, 0);
				});

				$this.on(`click.${NAME}`, 'button[data-url][data-prototype-id]', async function()
				{
					const $this = $(this);

					const url = $this.data('url');

					let $modal = modals[url];

					if (!$modal)
					{
						const html = await get(url);

						$modal = $(html);
						$modal.appendTo('body');

						modals[url] = $modal;
					}

					$modal.off('click', 'button[data-prototype]').on('click', 'button[data-prototype]', function()
					{
						let prototype = $(this).data('prototype');
						prototype = prototype.replace(/__name__/g, $list.data('index'));
						prototype = prototype.replace(/\w+_part_collection_/g, `${$this.data('prototype-id')}_`);
						prototype = prototype.replace(/\w+_part_collection/g, $this.data('prototype-name'));

						const $item = $(prototype).addClass('fade');

						$list.data('index', $list.data('index') + 1);
						$list.append($item);

						// (re)init
						sortable.destroy($list);
						sortable.init($list);

						// dispatch event
						$item.trigger('duo.event.part.addItem');

						updateWeight();
						toggleButton();

						$('html, body').scrollTop($item.offset().top);

						window.setTimeout(() => $item.addClass('show'), 250);
					}).modal('show');
				});

				$list.on('sortupdate', updateWeight);

				sortable.init($list);
			});
		},

		/**
		 * Destroy
		 *
		 * @param {string|HTMLElement|jQuery} selector
		 */
		destroy: selector =>
		{
			const $selector = _$(selector);

			$selector.off(`click.${NAME}`);
			$selector.find('button[data-url][data-prototype-id]').each(function()
			{
				const url = $(this).data('url');

				if (!modals[url])
				{
					return;
				}

				modals[url].remove();

				delete modals[url];
			});

			sortable.destroy($selector.find('> .sortable-list').off(`click.${NAME}`));
		}
	};

	$(window).on(`load.${NAME}`, () => methods.init(SELECTOR));

	return methods;
})($);