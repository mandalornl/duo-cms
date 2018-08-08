import $ from 'jquery';
import 'bootstrap-datepicker';

require('bootstrap-datepicker/dist/css/bootstrap-datepicker3.css');

export default ($ =>
{
	const NAME = 'datepicker';
	const SELECTOR = `.${NAME}`;

	const defaults = {
		calendarWeeks: true,
		clearBtn: true,
		format: 'dd-mm-yyyy'
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
		 * @param {{}} [config]
		 */
		init: (selector, config = {}) =>
		{
			config = Object.assign({}, defaults, config);

			_$(selector).each(function()
			{
				const $this = $(this);

				if ($this.data(`init.${NAME}`))
				{
					return;
				}

				$this.attr('placeholder', config.format).datepicker(config).data(`init.${NAME}`, true);
			});
		},

		/**
		 * Destroy
		 *
		 * @param {string|HTMLElement|jQuery} selector
		 */
		destroy: selector => _$(selector).removeData(`init.${NAME}`).datepicker('destroy')
	};

	$(window).on(`load.${NAME}`, () => methods.init(SELECTOR));

	return methods;
})($);