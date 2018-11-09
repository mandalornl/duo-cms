import $ from 'jquery';
import 'select2';

import md from 'duo/AdminBundle/Resources/webpack/js/util/mobiledetect';

require('select2/dist/css/select2.css');
require('select2-theme-bootstrap4/dist/select2-bootstrap.css');

// TODO: import proper i18n
// import 'select2/dist/js/i18n/nl';

export default ($ =>
{
	const NAME = 'select2';
	const SELECTOR = `.${NAME}`;

	const defaults = {
		theme: 'bootstrap',
		width: '100%',
		dropdownAutoWidth: true,
		allowClear: true
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
		init: (selector, options = {}) =>
		{
			if (md.mobile() || md.tablet())
			{
				return;
			}

			options = $.extend(true, {}, defaults, options);

			_$(selector).each(function()
			{
				const $this = $(this);

				if ($this.data(`init.${NAME}`))
				{
					return;
				}

				const _options = $.extend(true, {}, {
					language: window.locale.slice(0, 2),
					placeholder: $this.data('placeholder') || 'Please choose',
					minimumResultsForSearch: $this.find('option').length <= 10 ? Infinity : 10
				}, options);

				$this.select2(_options).data(`init.${NAME}`, true);
			});
		},

		/**
		 * Destroy
		 *
		 * @param {string|HTMLElement|jQuery} selector
		 */
		destroy: selector => _$(selector).removeData(`init.${NAME}`).select2('destroy')
	};

	$(window).on(`load.${NAME}`, () => methods.init(SELECTOR));

	return methods;
})($);