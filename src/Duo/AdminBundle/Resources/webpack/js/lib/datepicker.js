import $ from 'jquery';
import '@chenfengyuan/datepicker';
import '@chenfengyuan/datepicker/dist/datepicker.css'

export default ($ =>
{
	const NAME = 'datepicker';
	const SELECTOR = `[data-init="${NAME}"]`;

	const DEFAULTS = {
		weekStart: 1,
		format: 'dd-mm-yyyy',
		autoHide: true
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
		init: (selector, options = {}) =>
		{
			_$(selector).each(function()
			{
				const $this = $(this);

				if ($this.data(`init.${NAME}`))
				{
					return;
				}

				const config = Object.assign({}, DEFAULTS, options);

				$this.attr('placeholder', config.format).datepicker(config).data(`init.${NAME}`, true);
			});
		},

		/**
		 * Destroy
		 *
		 * @param {String|HTMLElement|jQuery} selector
		 */
		destroy: selector =>
		{
			_$(selector).removeData(`init.${NAME}`).datepicker('destroy');
		}
	};

	$(window).on(`load.${NAME}`, () => methods.init(SELECTOR));

	return methods;
})($);
