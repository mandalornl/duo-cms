import $ from 'jquery';

import {get} from '../util/api';
import * as loader from '../util/loader';

const pluginName = 'nestable';

const defaults = {
	draggableSelector	: '[draggable]',
	dropZoneSelector	: '.nestable-dropzone',
	dragEnterSelector	: '.nestable-text',
	listSelector		: '.nestable-list',
	itemSelector		: '.nestable-item',
	btnSelector			: '.nestable-btn',

	iconOpenClass		: 'icon-folder-open',
	iconCloseClass		: 'icon-folder',

	onDragStart			: function(e) {},
	onDragEnter			: function(e) {},
	onDragLeave			: function(e) {},
	onDragOver			: function(e) {},
	onDrop				: function(e, data) {},
	onDrag				: function(e) {},
	onDragEnd			: function(e) {}
};

const methods = {
	init: function(options)
	{
		options = $.extend({}, defaults, options);

		return this.each(function()
		{
			const $this = $(this);

			if ($this.data(`initialized.${pluginName}`))
			{
				return;
			}

			$this.data(`initialized.${pluginName}`, true);

			/**
			 * @type {jQuery}
			 */
			let $draggable;

			/**
			 * @type {jQuery}
			 */
			let $dragEnter;

			/**
			 * @type {jQuery}
			 */
			let $previousList;

			/**
			 * @type {jQuery}
			 */
			let $newList;

			/**
			 * @type {jQuery}
			 */
			let $sibling;

			/**
			 * @type {jQuery|HTMLElement}
			 */
			const $dropZone = $(`<li class="${options.dropZoneSelector.replace('.', '')}" />`);

			/**
			 * Get data
			 *
			 * @param {{}} [data= {}]
			 *
			 * @returns {{}}
			 */
			const getData = (data = {}) =>
			{
				return $.extend({
					$previousList: $previousList,
					$newList: $newList,
					$sibling: $sibling,
					$item: $draggable
				}, data);
			};

			/**
			 * Update button after drop
			 *
			 * @param {jQuery} $list
			 */
			const updateCollapsibleButtonAfterDrop = ($list) =>
			{
				if (!$list)
				{
					return;
				}

				const $item = $list.closest(options.itemSelector);
				if (!$item.length)
				{
					return;
				}

				const $btn = $item.find(`> ${options.dragEnterSelector} ${options.btnSelector}`);
				const $icon = $btn.find('i');

				if ($item.find(`> ${options.listSelector} > ${options.itemSelector}`).length)
				{
					$btn.removeClass('d-none');
					$icon.removeClass(options.iconCloseClass).addClass(options.iconOpenClass);
					return;
				}

				$btn.addClass('d-none');
				$icon.removeClass(options.iconOpenClass).addClass(options.iconCloseClass);
			};

			$this.on({
				dragstart: function(e)
				{
					const $target = $(e.target);
					if (!$target.is(options.draggableSelector))
					{
						return false;
					}

					$target.css('opacity', .5);

					$draggable = $target;

					$previousList = $target.closest(options.listSelector);

					e.originalEvent.dataTransfer.effectAllowed = 'move';

					options.onDragStart(e);
				},

				drag: function(e)
				{
					options.onDrag(e);
				},

				dragend: function(e)
				{
					$(e.target).css('opacity', '');

					$this.find('.over').removeClass('over');

					$dropZone.detach();

					$dragEnter = null;

					options.onDragEnd(e);
				}
			}, options.draggableSelector);

			// dragEnter
			$this.on({
				dragenter: function(e)
				{
					e.preventDefault();

					const $this = $(this);
					const $item = $this.closest(options.itemSelector);

					// ignore dragged element
					if ($draggable.is($item) || $draggable.find(options.draggableSelector).is($item))
					{
						return false;
					}

					// don't insert dropzone before dragged element
					if (!$item.next(options.itemSelector).is($draggable))
					{
						$dropZone.insertAfter($item);
					}

					const $btn = $this.find(options.btnSelector);

					// cannot enter element when children aren't loaded yet
					if (!$btn.hasClass('d-none') && !$item.find(`> ${options.listSelector} > ${options.itemSelector}`).length)
					{
						return false;
					}

					$dragEnter = $item;

					$this.addClass('over');

					options.onDragEnter(e);
				},

				dragleave: function(e)
				{
					const $this = $(this);

					$this.removeClass('over');

					$dragEnter = null;

					options.onDragLeave(e);
				},

				dragover: function(e)
				{
					e.preventDefault();

					e.originalEvent.dataTransfer.dropEffect = 'move';

					options.onDragOver(e);

					return false;
				},

				drop: function(e)
				{
					e.stopPropagation();

					if (!$dragEnter)
					{
						return false;
					}

					const $btn = $dragEnter.find(`> ${options.dragEnterSelector} ${options.btnSelector}`);

					// cannot enter element when children aren't loaded yet
					if (!$btn.hasClass('d-none') && !$dragEnter.find(`> ${options.listSelector} > ${options.itemSelector}`).length)
					{
						return false;
					}

					let $list = $dragEnter.find(`> ${options.listSelector}`);
					if (!$list.length)
					{
						$list = $(`<ul class="${options.listSelector.replace('.', '')}" />`);
						$list.data('id', $dragEnter.data('id'));

						$dragEnter.append($list);
					}

					$list.removeClass('d-none').append($draggable);

					$newList = $list;
					$sibling = $draggable.prev(options.itemSelector);

					updateCollapsibleButtonAfterDrop($previousList);
					updateCollapsibleButtonAfterDrop($newList);

					options.onDrop(e, getData());

					return false;
				}
			}, options.dragEnterSelector);

			// dropzone
			$this.on({
				dragenter: function(e)
				{
					e.preventDefault();

					$dropZone.addClass('over');

					options.onDragEnter(e);
				},

				dragleave: function(e)
				{
					$dropZone.removeClass('over');

					options.onDragLeave(e);
				},

				dragover: function(e)
				{
					e.preventDefault();

					e.originalEvent.dataTransfer.dropEffect = 'move';

					options.onDragOver(e);

					return false;
				},

				drop: function(e)
				{
					e.stopPropagation();

					if (!$dropZone.hasClass('over'))
					{
						return false;
					}

					$draggable.insertBefore($dropZone);

					$newList = $draggable.closest(options.listSelector);
					$sibling = $draggable.prev(options.itemSelector);

					updateCollapsibleButtonAfterDrop($previousList);
					updateCollapsibleButtonAfterDrop($newList);

					options.onDrop(e, getData());

					return false;
				}
			}, options.dropZoneSelector);

			$this.on('click', options.btnSelector, async function(e)
			{
				e.preventDefault();

				const $btn = $(this);
				const $list = $btn.closest(options.itemSelector).find(`> ${options.listSelector}`);

				if ($list.hasClass('d-none'))
				{
					if (!$list.find(`> ${options.itemSelector}`).length && $btn.data('url'))
					{
						if ($list.data('loading'))
						{
							return;
						}

						$list.data('loading', true);

						loader.show();

						const html = await get($btn.data('url'));
						if (!html)
						{
							return;
						}

						$list.replaceWith(html).removeData('loading');

						loader.hide();
					}

					$list.removeClass('d-none');
					$btn.find('i').removeClass(options.iconCloseClass).addClass(options.iconOpenClass);
					return;
				}

				$list.addClass('d-none');
				$btn.find('i').removeClass(options.iconOpenClass).addClass(options.iconCloseClass);
			});
		});
	}
};

$.fn[pluginName] = function(method, options)
{
	if (methods[method])
	{
		return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
	}
	else if (typeof method === 'object' || !method)
	{
		return methods.init.apply(this, arguments);
	}
	else
	{
		$.error(`Method ${method} doesn't exist on jQuery.${pluginName}`);
	}
};