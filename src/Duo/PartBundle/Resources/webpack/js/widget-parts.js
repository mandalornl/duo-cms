import $ from 'jquery';

import sortable from 'duo/AdminBundle/Resources/webpack/js/lib/sortable';
import {get} from 'duo/AdminBundle/Resources/webpack/js/util/api';

export default ($ =>
{
	const NAME = 'parts';
	const SELECTOR = `.widget-${NAME}`;

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

				const $section = $this.find('> .tab-content > .tab-pane > .section');
				const $list = $section.find('> .sortable-list');

				let index = $list.find('> .sortable-item').length;

				$list.each(function()
				{
					const $this = $(this);

					$this.next('button').toggleClass('d-none', !$this.find('> .sortable-item').length);
				});

				$section.on(`click.${NAME}`, '> button[data-url][data-prototype-id]', async function(e)
				{
					e.preventDefault();

					const $this = $(this);
					const $section = $this.closest('.section');
					const $list = $section.find('> .sortable-list');

					const url = $this.data('url');

					let $modal = modals[url];

					if (!$modal)
					{
						const html = await get(url);

						$modal = $(html);
						$modal.appendTo('body');

						modals[url] = $modal;
					}

					$modal.find('[data-type]').addClass('d-none');

					($section.data('types') || []).forEach(type =>
					{
						$modal.find(`[data-type="${type}"]`).removeClass('d-none');
					});

					$modal.off('click', 'button[data-prototype]').on('click', 'button[data-prototype]', function(e)
					{
						e.preventDefault();

						let prototype = $(this).data('prototype');
						prototype = prototype.replace(/__name__/g, index++);
						prototype = prototype.replace(/\w+_part_collection_/g, `${$this.data('prototype-id')}_`);
						prototype = prototype.replace(/\w+_part_collection/g, $this.data('prototype-name'));

						const $item = $(prototype).addClass('fade');

						$item.find('[id$="_section"]').val($section.data('section'));

						$list.append($item);

						// (re) init sortable
						sortable.destroy($list);
						sortable.init($list);

						// dispatch event
						$item.trigger('duo.event.part.addItem');

						// toggle button
						$list.next('button').toggleClass('d-none', !$list.find('> .sortable-item').length);

						// update weight
						$list.find('> .sortable-item [id$="_weight"]').each(function(weight)
						{
							this.value = weight;
						});

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
					}).modal('show');
				});

				$list.on(`click.${NAME}`, 'button[data-dismiss="sortable-item"]', function(e)
				{
					e.preventDefault();

					const $this = $(this);

					const $list = $this.closest('.sortable-list');
					const $item = $this.closest('.sortable-item');

					$item.addClass('fade show');

					window.setTimeout(() =>
					{
						$item.removeClass('show').on('bsTransitionEnd', () =>
						{
							// dispatch event
							$item.trigger('duo.event.part.removeItem');
							$item.remove();

							// toggle button
							$list.next('button').toggleClass('d-none', !$list.find('> .sortable-item').length);

							// update weight
							$list.find('> .sortable-item [id$="_weight"]').each(function(weight)
							{
								this.value = weight;
							});
						}).emulateTransitionEnd(150);
					}, 0);
				});

				$list.on('sortupdate', function()
				{
					$(this).find('> .sortable-item [id$="_weight"]').each(function(weight)
					{
						this.value = weight;
					});
				});

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

			const $section = $selector.find('> .tab-content > .tab-pane > .section');
			$section.off(`click.${NAME}`);

			const $list = $section.find('> .sortable-list');
			$list.off(`click.${NAME}`);

			$section.find('> button[data-url][data-prototype-id]').each(function()
			{
				const url = $(this).data('url');

				if (!modals[url])
				{
					return;
				}

				modals[url].remove();

				delete modals[url];
			});

			sortable.destroy($list);
		}
	};

	$(window).on(`load.${NAME}`, () => methods.init(SELECTOR));

	return methods;
})($);