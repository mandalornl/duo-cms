import $ from 'jquery';
import sortable from 'html5sortable/dist/html5sortable.es';

export default ($ =>
{
	const NAME = 'sortable';
	const SELECTOR = `[data-init="${NAME}"]`;

	const DEFAULT = {
		items: '.sortable-item:not(:disabled):not(.disabled)',
		handle: '.sortable-handle',
		placeholderClass: 'sortable-placeholder',
		draggingClass: 'sortable-dragging'
	};

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
		 * @param {{}} [options]
		 */
		init: (selector, options) =>
		{
			_$(selector).each(function()
			{
				const $this = $(this);

				if ($this.data(`init.${NAME}`))
				{
					return;
				}

				$this.data(`init.${NAME}`, true);

				const config = Object.assign({}, DEFAULT, options);

				sortable(this, config);
			});
		},

		/**
		 * Destroy
		 *
		 * @param {string|HTMLElement|jquery} selector
		 */
		destroy: selector =>
		{
			const $selector = _$(selector);

			$selector.removeData(`init.${NAME}`);

			sortable($selector.get(), 'destroy');
		}
	};

	$(window).on(`load.${NAME}`, () => methods.init(SELECTOR));

	return methods;
})($);
