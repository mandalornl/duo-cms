import $ from 'jquery';
import 'select2';

import md from 'duo/AdminBundle/Resources/webpack/js/util/mobiledetect';

require('select2/dist/css/select2.css');
require('select2-theme-bootstrap4/dist/select2-bootstrap.css');

// TODO: import proper i18n
//import 'select2/dist/js/i18n/nl';

export default ($ =>
{
	const NAME = 'autocomplete';
	const SELECTOR = `.${NAME}`;

	const defaults = {
		theme: 'bootstrap',
		width: '100%',
		dropdownAutoWidth: true,
		allowClear: true,
		minimumInputLength: 2
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
			options = $.extend(true, {}, defaults, options);

			_$(selector).each(function()
			{
				const $this = $(this);

				if ($this.data(`init.${NAME}`))
				{
					return;
				}

				const _options = $.extend(true, {}, {
					placeholder: $this.data('placeholder') || 'Enter keyword(s)',
					ajax: {
						url: $this.data('url'),
						dataType: 'json',
						delay: (md.mobile() || md.tablet()) ? 1200 : 600,
						cache: true,

						data: (params) =>
						{
							return {
								q: params.term,
								page: params.page || 1
							};
						},

						processResults: (data, params) =>
						{
							params.page = params.page || 1;

							return {
								results: data.result,
								pagination: {
									more: (params.page * 10) < data.count
								}
							};
						}
					}
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