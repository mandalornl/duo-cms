import $ from 'jquery';

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
	onDragEnd			: function(e) {},
	onExpand			: function(e) {},
	onCollapse			: function(e) {}
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
			let $draggedOver;

			/**
			 * @type {jQuery}
			 */
			let $prevList;

			/**
			 * @type {jQuery}
			 */
			let $newList;

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
					$prevList: $prevList,
					$newList: $newList,
					$item: $draggable
				}, data);
			};

			/**
			 * Update button after drop
			 *
			 * @param {jQuery} $item
			 */
			const updateButtonAfterDrop = ($item) =>
			{
				if (!$item)
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
						return;
					}

					$target.css('opacity', .5);

					$draggable = $target;

					$prevList = $target.closest(options.listSelector);

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

					$draggedOver = null;

					options.onDragEnd(e);
				}
			}, options.draggableSelector);

			$this.on({
				dragenter: function(e)
				{
					e.preventDefault();

					const $this = $(this);
					const $item = $this.closest(options.itemSelector);

					if ($draggable.is($item) || $draggable.find(options.draggableSelector).is($item))
					{
						return;
					}

					$dropZone.insertAfter($item);

					$draggedOver = $item;

					options.onDragEnter(e, getData());

					if ($item.data('children') && !$item.find(`> ${options.listSelector} > ${options.itemSelector}`).length)
					{
						return;
					}

					$this.addClass('over');
				},

				dragleave: function(e)
				{
					const $this = $(this);

					$this.removeClass('over');

					options.onDragLeave(e);
				},

				dragover: function(e)
				{
					e.preventDefault();

					e.originalEvent.dataTransfer.dropEffect = 'move';

					options.onDragOver(e);
				},

				drop: function(e)
				{
					if ($draggedOver.data('children') && !$draggedOver.find(`> ${options.listSelector} > ${options.itemSelector}`).length)
					{
						return;
					}

					let $list = $draggedOver.find(`> ${options.listSelector}`);
					if (!$list.length)
					{
						$list = $(`<ul class="${options.listSelector.replace('.', '')}"/>`);

						$draggedOver.append($list);
					}

					e.stopPropagation();

					$list.removeClass('d-none');

					$draggable.appendTo($list);

					$newList = $list;

					updateButtonAfterDrop($prevList.closest(options.itemSelector));
					updateButtonAfterDrop($newList.closest(options.itemSelector));

					options.onDrop(e, getData());
				}
			}, options.dragEnterSelector);

			$this.on({
				dragenter: function()
				{
					$dropZone.addClass('over');
				},

				dragleave: function()
				{
					$dropZone.removeClass('over');
				},

				dragover: function(e)
				{
					e.preventDefault();

					e.originalEvent.dataTransfer.dropEffect = 'move';

					options.onDragOver(e);
				},

				drop: function(e)
				{
					e.stopPropagation();

					$draggable.insertAfter($dropZone);

					$newList = $draggable.closest(options.listSelector);

					updateButtonAfterDrop($prevList.closest(options.itemSelector));
					updateButtonAfterDrop($newList.closest(options.itemSelector));

					options.onDrop(e, getData());
				}
			}, options.dropZoneSelector);

			$this.on('click', options.btnSelector, async function(e)
			{
				e.preventDefault();

				const $btn = $(this);
				const $item = $btn.closest(options.itemSelector);
				const $list = $item.find(`> ${options.listSelector}`);

				if (!$list.length || $list.hasClass('d-none'))
				{
					await options.onExpand.call(this, e);

					$item.removeAttr('data-children');

					$list.removeClass('d-none');
					$btn.find('i').removeClass(options.iconCloseClass).addClass(options.iconOpenClass);
					return;
				}

				await options.onCollapse.call(this, e);

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
		$.error(`Method ${method} does not exist on jQuery.${pluginName}`);
	}
};

// $this.on({
// 	dragstart: function(e)
// 	{
// 		const $target = $(e.target);
// 		if (!$target.is(options.draggableSelector))
// 		{
// 			return false;
// 		}
//
// 		$target.css('opacity', .5);
//
// 		$draggable = $target;
//
// 		$prevList = $target.closest(options.listSelector);
//
// 		e.originalEvent.dataTransfer.effectAllowed = 'move';
//
// 		options.onDragStart(e);
// 	},
//
// 	drag: function(e)
// 	{
// 		if (!$draggedOver)
// 		{
// 			return;
// 		}
//
// 		// if ($draggedOver.data('hasList') && (e.pageX - pageX) > options.xThreshold)
// 		// {
// 		// 	$draggedOver.find(`> ${options.listSelector}`).append($dropZone);
// 		// }
// 		// else
// 		// {
// 		// 	$dropZone.insertAfter($draggedOver);
// 		// }
//
// 		// const y = $draggedOver.offset().top;
// 		// const height = $draggedOver.outerHeight();
// 		//
// 		// if (!$draggedOver.data('hasPrevSibling') && !$draggedOver.data('hasNextSibling'))
// 		// {
// 		// 	if (e.pageY > y && e.pageY < (y + (height / 2)))
// 		// 	{
// 		// 		$dropZone.insertBefore($draggedOver);
// 		// 	}
// 		// 	else
// 		// 	{
// 		// 		if (e.pageY > (y + (height / 2)) && e.pageY < (y + height))
// 		// 		{
// 		// 			$dropZone.insertAfter($draggedOver);
// 		// 		}
// 		//
// 		// 		if ($draggedOver.data('hasList'))
// 		// 		{
// 		// 			if ((e.pageX - pageX) > options.xThreshold)
// 		// 			{
// 		// 				$draggedOver.find(`> ${options.listSelector}`).append($dropZone);
// 		// 			}
// 		// 		}
// 		// 	}
// 		// }
// 		// else
// 		// {
// 		// 	if (e.pageY > y && e.pageY < (y + height))
// 		// 	{
// 		// 		$dropZone.insertAfter($draggedOver);
// 		// 	}
// 		//
// 		// 	if ($draggedOver.data('hasList'))
// 		// 	{
// 		// 		if ((e.pageX - pageX) > options.xThreshold)
// 		// 		{
// 		// 			$draggedOver.find(`> ${options.listSelector}`).append($dropZone);
// 		// 		}
// 		// 	}
// 		// }
//
// 		options.onDrag(e);
// 	},
//
// 	dragend: function(e)
// 	{
// 		$(e.target).css('opacity', '');
//
// 		$dropZone.detach();
//
// 		$draggedOver = null;
//
// 		options.onDragEnd(e, getData());
// 	}
// }, options.draggableSelector);
//
// $this.on({
// 	dragenter: function(e)
// 	{
// 		e.preventDefault();
//
// 		const $this = $(this).closest(options.itemSelector);
//
// 		if ($draggable.is($this) || $draggable.find(options.draggableSelector).is($this))
// 		{
// 			return;
// 		}
//
// 		const $list = $this.find(`> ${options.listSelector}`);
//
// 		$this.data({
// 			hasPrevSibling: $this.prev(options.itemSelector).length !== 0,
// 			hasNextSibling: $this.next(options.itemSelector).length !== 0,
// 			hasList: $list.length !== 0
// 		});
//
// 		$this.find(`> ${options.dragEnterSelector}`).addClass('over');
//
// 		$dropZone.insertAfter($this);
//
// 		$draggedOver = $this;
//
// 		pageX = e.pageX;
//
// 		options.onDragEnter(e);
// 	},
//
// 	dragleave: function(e)
// 	{
// 		if ($draggedOver)
// 		{
// 			$draggedOver.find(`> ${options.dragEnterSelector}`).removeClass('over');
// 		}
//
// 		options.onDragLeave(e);
// 	},
//
// 	dragover: function(e)
// 	{
// 		e.preventDefault();
//
// 		e.originalEvent.dataTransfer.dropEffect = 'move';
//
// 		options.onDragOver(e);
// 	},
//
// 	drop: function(e)
// 	{
// 		const $list = $draggedOver.find(`> ${options.listSelector}`);
// 		console.log($list);
// 		if (!$list.length)
// 		{
// 			return;
// 		}
//
// 		e.stopPropagation();
//
// 		$draggable.appendTo($list);
//
// 		$newList = $list;
//
// 		updateButtons($prevList.closest(options.itemSelector));
// 		updateButtons($newList.closest(options.itemSelector));
//
// 		options.onDrop(e, getData());
// 	}
// }, options.dragEnterSelector);
//
// $this.on({
// 	dragenter: function(e)
// 	{
// 		$dropZone.addClass('over');
//
// 		pageX = e.pageX;
// 	},
//
// 	dragleave: function()
// 	{
// 		$dropZone.removeClass('over');
// 	},
//
// 	dragover: function(e)
// 	{
// 		e.preventDefault();
//
// 		e.originalEvent.dataTransfer.dropEffect = 'move';
//
// 		options.onDragOver(e);
// 	},
//
// 	drop: function(e)
// 	{
// 		e.stopPropagation();
//
// 		$draggable.insertAfter($dropZone);
//
// 		$newList = $draggable.closest(options.listSelector);
//
// 		updateButtons($prevList.closest(options.itemSelector));
// 		updateButtons($newList.closest(options.itemSelector));
//
// 		options.onDrop(e, getData());
// 	}
// }, options.dropZoneSelector);