import $ from 'jquery';

import * as sortable from 'duo/AdminBundle/Resources/webpack/js/lib/sortable';
import {get} from 'duo/AdminBundle/Resources/webpack/js/util/api';

const modals = {};

/**
 * Initialize
 *
 * @param {string|jQuery|HTMLElement} [selector = '.part-widget']
 */
export default (selector = '.part-widget') =>
{
	const $selector = (selector instanceof jQuery || 'jquery' in Object(selector)) ? selector : $(selector);

	$selector.each(function()
	{
		const $this = $(this);

		if ($this.data('init.part-widget'))
		{
			return;
		}

		$this.data('init.part-widget', true);

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

		$list.on('click', '[data-dismiss="part-widget-item"]', function(e)
		{
			e.preventDefault();

			const $item = $(this).closest('.part-widget-item');

			// dispatch event
			$item.trigger('duo.event.part.remove');
			$item.remove();

			updateWeight();
			toggleButton();
		});

		$this.on('click', 'button[data-url][data-prototype-id]', async function()
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
					sortable.init({
						selector: $list
					});

					// dispatch event
					$item.trigger('duo.event.part.add');

					updateWeight();
					toggleButton();
				});

				modals[$this.data('url')] = $modal;
			}

			$modal.modal('show');
		});

		$list.on('sortupdate', updateWeight);

		sortable.init({
			selector: $list
		});
	});
};