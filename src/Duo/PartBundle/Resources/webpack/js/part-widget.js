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

				let newIndex = $list.find('.sortable-item').length;

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
					$this.find('button[data-url][data-prototype-id]:last').parent().toggleClass('d-none', !$list.find('.sortable-item').length);
				};

				$list.on(`click.${NAME}`, '[data-dismiss="sortable-item"]', function(e)
				{
					e.preventDefault();

					const $item = $(this).closest('.sortable-item');

					// dispatch event
					$item.trigger('duo.event.part.removeItem');
					$item.remove();

					updateWeight();
					toggleButton();
				});

				$this.on(`click.${NAME}`, 'button[data-url][data-prototype-id]', async function()
				{
					const $this = $(this);

					let $modal = modals[$this.data('url')];

					if (!$modal)
					{
						const html = await get($this.data('url'));

						$modal = $(html);
						$modal.appendTo('body');
						$modal.on('click', 'button[data-prototype]', function()
						{
							let prototype = $(this).data('prototype').split('__name__').join(newIndex++);
							prototype = prototype.replace(/\w+_part_collection_/g, `${$this.data('prototype-id')}_`);
							prototype = prototype.replace(/\w+_part_collection\[/g, `${$this.data('prototype-name')}[`);

							const $item = $(prototype);
							$list.append($item);

							// (re)init
							sortable.destroy($list);
							sortable.init($list);

							// dispatch event
							$item.trigger('duo.event.part.addItem');

							updateWeight();
							toggleButton();
						});

						modals[$this.data('url')] = $modal;
					}

					$modal.modal('show');
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

			Object.keys(modals).forEach(key =>
			{
				modals[key].remove();

				delete modals[key];
			});
		}
	};

	$(window).on(`load.${NAME}`, () => methods.init(SELECTOR));

	return methods;
})($);