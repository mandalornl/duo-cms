import $ from 'jquery';

import '../jquery/sortable';
import * as wysiwyg from '../assets/wysiwyg';

/**
 * Initialize
 *
 * @param {string|jQuery|HTMLElement} [selector = '.page-parts']
 */
const init = (selector = '.page-parts') =>
{
	const $selector = (selector instanceof jQuery || 'jquery' in Object(selector)) ? selector : $(selector);

	$selector.each(function()
	{
		const $this = $(this);
		if ($this.data('initialized.page-parts'))
		{
			return;
		}

		$this.data('initialized.page-parts', true);

		const $list = $this.find('.sortable-list');

		let newWeight = $list.find('.sortable-item').length;

		/**
		 * Update sorting
		 */
		const updateSorting = () =>
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
			$this.find('[data-toggle="modal"]:last').parent().toggleClass('d-none', !$list.find('.sortable-item').length);
		};

		$list.on('click', '[data-dismiss="page-part"]', function(e)
		{
			e.preventDefault();

			const $item = $(this).closest('.page-part');

			const $wysiwyg = $item.find('.wysiwyg');
			if ($wysiwyg.length)
			{
				wysiwyg.destroy($wysiwyg.attr('id'));
			}

			$item.remove();

			updateSorting();
			toggleButton();
		});

		$list.sortable();

		$list.on('sortupdate', updateSorting);

		// move modal to body, this also fixes fixed positioning when using translate3d on parent
		const $modal = $this.find('.modal');
		if ($modal.length)
		{
			const $bodyModal = $('body > .modal:first');
			if ($bodyModal.length)
			{
				$modal.insertBefore($bodyModal);
			}
			else
			{
				$modal.appendTo('body');
			}
		}

		// add new item
		$modal.on('click', 'button[data-prototype]', function()
		{
			const $item = $($(this).data('prototype').split('__name__').join(newWeight++));
			$list.append($item);
			$list.sortable();

			const $wysiwyg = $item.find('.wysiwyg');
			if ($wysiwyg.length)
			{
				wysiwyg.init({}, $wysiwyg);
			}

			toggleButton();
		});
	});
};

export {init};