import $ from 'jquery';

import {get} from '../api';
import '../jquery/sortable';
import * as wysiwyg from '../assets/wysiwyg';
import * as loader from '../util/loader';

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
			// fetch modal
			if (!$modal)
			{
				loader.show();

				const html = await get($(this).data('url'));

				$modal = $(html);
				$modal.appendTo('body');

				loader.hide();

				$modal.on('click', 'button[data-prototype]', function(e)
				{
					e.preventDefault();

					const $item = $($(this).data('prototype').split('__name__').join(newIndex++));
					$list.append($item);
					$list.sortable();

					const $wysiwyg = $item.find('.wysiwyg');
					if ($wysiwyg.length)
					{
						wysiwyg.init({}, $wysiwyg);
					}

					updateWeight();
					toggleButton();
				});
			}

			$modal.modal('show');
		});

		$list.sortable().on('sortupdate', updateWeight);
	});
};

export {init};