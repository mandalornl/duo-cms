import $ from 'jquery';

import '../jquery/sortable';
import * as wysiwyg from '../assets/wysiwyg';
import autoComplete from '../assets/autocomplete';
import {get} from '../util/api';

/**
 * Initialize
 *
 * @param {string|jQuery|HTMLElement} [selector = '.part-list']
 */
const init = (selector = '.part-list') =>
{
	const $selector = (selector instanceof jQuery || 'jquery' in Object(selector)) ? selector : $(selector);

	$selector.each(function()
	{
		const $this = $(this);
		if ($this.data('initialized.parts'))
		{
			return;
		}

		$this.data('initialized.parts', true);

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
			$this.find('button[data-url]:last').parent().toggleClass('d-none', !$list.find('.sortable-item').length);
		};

		$list.on('click', '[data-dismiss="part-item"]', function(e)
		{
			e.preventDefault();

			const $item = $(this).closest('.part-item');

			const $wysiwyg = $item.find('.wysiwyg');
			if ($wysiwyg.length)
			{
				wysiwyg.destroy($wysiwyg.attr('id'));
			}

			$item.remove();

			updateWeight();
			toggleButton();
		});

		let $modal;

		$this.on('click', 'button[data-url]', async function()
		{
			const $this = $(this);

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
					$list.sortable();

					// init assets
					wysiwyg.init($item.find('.wysiwyg'));
					autoComplete($item.find('.autocomplete'));

					updateWeight();
					toggleButton();
				});
			}

			$modal.modal('show');
		});

		$list.sortable().on('sortupdate', updateWeight);
	});
};

export default init;