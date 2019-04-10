import $ from 'jquery';

import loader from 'Duo/AdminBundle/Resources/webpack/js/util/loader';
import {get, post} from 'Duo/AdminBundle/Resources/webpack/js/util/api';
import nestable from 'Duo/AdminBundle/Resources/webpack/js/lib/nestable';

export default ($ =>
{
	const NAME = 'nestable-tree';
	const SELECTOR = `.${NAME}`;

	/**
	 * Get jQuery
	 *
	 * @param {String|HTMLElement|jQuery} selector
	 *
	 * @returns {jQuery}
	 */
	const _$ = selector => (selector instanceof jQuery || 'jquery' in Object(selector)) ? selector : $(selector);

	const methods = {

		SELECTOR: SELECTOR,

		/**
		 * Initialize
		 *
		 * @param {String|HTMLElement|jQuery} selector
		 */
		init: (selector) =>
		{
			_$(selector).each(function()
			{
				const $this = $(this);

				if ($this.data(`init.${NAME}`))
				{
					return;
				}

				/**
				 * Update folder after drop
				 *
				 * @param {HTMLElement} list
				 */
				const updateFolderAfterDrop = list =>
				{
					if (!list)
					{
						return;
					}

					const $list = $(list);
					const $item = $list.closest('.nestable-item');

					if (!$item.length)
					{
						return;
					}

					const $folder = $item.find('> .nestable-handle .nestable-folder');

					if ($item.find('> .nestable-list > .nestable-item').length)
					{
						$folder.removeClass('d-none').addClass('open');
						$list.removeClass('d-none');

						return;
					}

					$folder.addClass('d-none').removeClass('open');
					$list.addClass('d-none');
				};

				/**
				 * On sort update
				 *
				 * @param {CustomEvent} event
				 *
				 * @returns {Promise<void>}
				 */
				const onSortUpdate = async event =>
				{
					const $item = $(event.detail.item);
					const $parent = $(event.detail.destination.container);
					const $prevSibling = $item.prev('.nestable-item');
					const $nextSibling = $item.next('.nestable-item');

					const formData = new FormData();
					formData.append('id', $item.data('id'));
					formData.append('parentId', $parent.data('id') || null);
					formData.append('prevSiblingId', $prevSibling.data('id') || null);
					formData.append('nextSiblingId', $nextSibling.data('id') || null);

					loader.show(true);

					const response = await post($this.data('move-to-url'), formData);

					loader.hide();

					if (!response.success)
					{
						// reset position
						const $origin = $(event.detail.origin.container);
						const $items = $origin.find('> .nestable-item');

						if ($items.length)
						{
							$item.insertAfter($items.eq(event.detail.origin.index - 1));

							return;
						}

						$origin.append($item);

						return;
					}

					updateFolderAfterDrop(event.detail.origin.container);
					updateFolderAfterDrop(event.detail.destination.container);
				};

				$this.on(`click.${NAME}`, '.nestable-folder', async function(event)
				{
					event.preventDefault();

					const $folder = $(this);
					const $list = $folder.closest('.nestable-item').find('> .nestable-list');

					if ($list.hasClass('d-none'))
					{
						if (!$list.find('> .nestable-item').length)
						{
							loader.show(true);

							const response = await get($folder.data('url'));

							loader.hide();

							if (!response.html)
							{
								return;
							}

							const $html = $(response.html).find('.nestable-list').attr('data-group', $list.data('group')).end();
							$list.replaceWith($html);

							// initialize sorting
							if ($this.hasClass('is-sortable'))
							{
								nestable.destroy($this);
								nestable.init($this, {
									onSortUpdate: onSortUpdate
								});
							}
						}

						$list.removeClass('d-none');
						$folder.addClass('open');

						return;
					}

					$list.addClass('d-none');
					$folder.removeClass('open');
				});

				// initialize sorting
				if ($this.hasClass('is-sortable'))
				{
					nestable.init($this, {
						onSortUpdate: onSortUpdate
					});
				}
			});
		},

		/**
		 * Destroy
		 *
		 * @param {String|HTMLElement|jQuery} selector
		 */
		destroy: selector =>
		{
			const $selector = _$(selector);
			$selector.removeData(`init.${NAME}`).off(`click.${NAME}`);

			nestable.destroy($selector.filter('.is-sortable'));
		}
	};

	$(window).on(`load.${NAME}`, () => methods.init(SELECTOR));

	return methods;
})($);
