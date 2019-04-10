import $ from 'jquery';
import sortable from 'html5sortable/dist/html5sortable.es';

export default ($ =>
{
	const NAME = 'sortable';
	const SELECTOR = `[data-init="${NAME}"]`;

	const DEFAULTS = {
		items: '.sortable-item:not(:disabled):not(.disabled)',
		handle: '.sortable-handle',
		placeholderClass: 'sortable-placeholder',
		draggingClass: 'sortable-dragging'
	};

	/**
	 * Get jQuery
	 *
	 * @param {String|HTMLElement|jQuery} selector
	 *
	 * @returns {jQuery}
	 */
	const _$ = selector => (selector instanceof jQuery || 'jquery' in Object(selector)) ? selector : $(selector);

	const methods = {

		SELECTOR: SELECTOR,

		/**
		 * Initialize
		 *
		 * @param {String|HTMLElement|jQuery} selector
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

				const config = Object.assign({}, DEFAULTS, options);

				sortable(this, config);
			});
		},

		/**
		 * Destroy
		 *
		 * @param {String|HTMLElement|jquery} selector
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
