import $ from 'jquery';
import sortable from 'html5sortable/dist/html5sortable.es';

/**
 * Initialize
 *
 * @param {{}} [options]
 */
const init = (options = {}) =>
{
	options = $.extend({}, {
		selector: '.sortable',
		items: '.sortable-item:not(:disabled):not(.disabled)',
		handle: '.sortable-handle',
		placeholderClass: 'sortable-placeholder',
		draggingClass: 'sortable-dragging',
	}, options);

	const $selector = (
		options.selector instanceof jQuery || 'jquery' in Object(options.selector)
	) ? options.selector : $(options.selector);

	$selector.each(function()
	{
		const $this = $(this);

		if ($this.data('init.sortable'))
		{
			return;
		}

		$this.data('init.sortable', true);

		sortable(this, options);
	});
};

/**
 * Destroy
 *
 * @param {string|jQuery|HTMLElement} [selector = '.sortable']
 */
const destroy = (selector = '.sortable') =>
{
	const $selector = (selector instanceof jQuery || 'jquery' in Object(selector)) ? selector : $(selector);

	$selector.removeData('init.sortable');

	sortable($selector.get(), 'destroy');
};

export {init, destroy};