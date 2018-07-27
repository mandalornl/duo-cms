import $ from 'jquery';
import sortable from 'html5sortable/dist/html5sortable.es';

import uniqid from 'duo/AdminBundle/Resources/webpack/js/util/uniqid';

/**
 * Initialize
 *
 * @param {{}} [options]
 */
const init = (options = {}) =>
{
	options = $.extend({}, {
		selector: '.nestable',
		onSortStart: (e) => {},
		onSortStop: (e) => {},
		onSortUpdate: (e) => {}
	}, options);

	const $selector = (
		options.selector instanceof jQuery || 'jquery' in Object(options.selector)
	) ? options.selector : $(options.selector);

	$selector.each(function()
	{
		const $this = $(this);

		if ($this.data('init.nestable'))
		{
			return;
		}

		$this.data('init.nestable', true);

		const $list = $this.find('.nestable-list:not(.nestable-async)');

		$list.attr('data-group', $list.data('group') || uniqid());

		sortable($list.get(), {
			items: '.nestable-item:not(:disabled):not(.disabled)',
			handle: '.nestable-handle',
			placeholderClass: 'nestable-placeholder',
			draggingClass: 'nestable-dragging',
			acceptFrom: `[data-group="${$list.data('group')}"]`
		});

		$list.on('sortstart', (e) =>
		{
			$list.each(function()
			{
				const $this = $(this);

				if ($this.find('> li').length || $this.closest('.nestable-dragging').length)
				{
					return;
				}

				$this.closest('.nestable-item').addClass('nestable-empty');
			});

			options.onSortStart(e);
		});

		$list.on('sortstop', (e) =>
		{
			$this.find('.nestable-empty').removeClass('nestable-empty');

			options.onSortStop(e);
		});

		$list.on('sortupdate', options.onSortUpdate);
	});
};

/**
 * Destroy
 *
 * @param {string|jQuery|HTMLElement} [selector = '.nestable']
 */
const destroy = (selector = '.nestable') =>
{
	const $selector = (selector instanceof jQuery || 'jquery' in Object(selector)) ? selector : $(selector);

	$selector.removeData('init.nestable');
	$selector.find('.nestable-list').removeAttr('data-group').off('sortstart sortstop sortupdate');

	sortable($selector.get(), 'destroy');
};

export {init, destroy};