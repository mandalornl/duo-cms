import $ from 'jquery';

import * as loader from 'duo/AdminBundle/Resources/webpack/js/util/loader';
import {get, post} from 'duo/AdminBundle/Resources/webpack/js/util/api';
import nestable from 'duo/AdminBundle/Resources/webpack/js/lib/nestable';

$(async () =>
{
	const $tree = $('.page-tree');

	if (!$tree.length)
	{
		return;
	}

	/**
	 * Update folder after drop
	 *
	 * @param {HTMLElement} list
	 */
	const updateFolderAfterDrop = (list) =>
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
	 * On sort update event
	 *
	 * @param {{}} e
	 *
	 * @returns {Promise<void>}
	 */
	const onSortUpdate = async (e) =>
	{
		const $item = $(e.detail.item);
		const $parent = $(e.detail.destination.container);
		const $prevSibling = $item.prev('li');
		const $nextSibling = $item.next('li');

		const formData = new FormData();
		formData.append('id', $item.data('id'));
		formData.append('parentId', $parent.data('id') || null);
		formData.append('prevSiblingId', $prevSibling.data('id') || null);
		formData.append('nextSiblingId', $nextSibling.data('id') || null);

		loader.show(true);

		const res = await post(`${$tree.data('url')}/json`, formData);

		loader.hide();

		if (!res)
		{
			// reset position
			const $origin = $(e.detail.origin.container);
			const $items = $origin.find('> .nestable-item');

			if ($items.length)
			{
				$item.insertAfter($items.eq(e.detail.origin.index - 1));

				return;
			}

			$origin.append($item);

			return;
		}

		updateFolderAfterDrop(e.detail.origin.container);
		updateFolderAfterDrop(e.detail.destination.container);
	};

	$tree.on('click', '.nestable-folder', async function(e)
	{
		e.preventDefault();

		const $this = $(this);
		const $list = $this.closest('.nestable-item').find('> .nestable-list');

		if ($list.hasClass('d-none'))
		{
			if (!$list.find('> .nestable-item').length)
			{
				loader.show(true);

				const html = await get($this.data('url'));

				if (!html)
				{
					return;
				}

				const $html = $(html).find('.nestable-list').attr('data-group', $list.data('group')).end();
				$list.replaceWith($html);

				nestable.destroy($tree);
				nestable.init($tree, {
					onSortUpdate: onSortUpdate
				});

				loader.hide();
			}

			$list.removeClass('d-none');
			$this.addClass('open');

			return;
		}

		$list.addClass('d-none');
		$this.removeClass('open');
	});

	nestable.init($tree, {
		onSortUpdate: onSortUpdate
	});
});