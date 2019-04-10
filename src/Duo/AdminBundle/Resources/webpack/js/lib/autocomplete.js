import 'select2';

import md from 'Duo/AdminBundle/Resources/webpack/js/util/mobiledetect';

require('select2/dist/css/select2.css');
require('select2-theme-bootstrap4/dist/select2-bootstrap.css');

const locale = window.locale.slice(0, 2);
const locales = require.context('select2/src/js/select2/i18n/', false, /\.js$/);

export default ($ =>
{
	const NAME = 'autocomplete';
	const SELECTOR = `[data-init="${NAME}"]`;

	const DEFAULTS = {
		theme: 'bootstrap',
		width: '100%',
		dropdownAutoWidth: true,
		allowClear: true,
		minimumInputLength: 2,
		language: (() =>
		{
			const key = `./${locale}.js`;

			if (locales.keys().indexOf(key) === -1)
			{
				return 'en';
			}

			return Object.assign({}, locales(key));
		})(),
		ajax: {
			delay: (md.mobile() || md.tablet()) ? 500 : 250
		}
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

				const config = $.extend(true, {}, DEFAULTS, {
					placeholder: $this.data('placeholder') || 'Enter keyword(s)',
					ajax: {
						url: $this.data('url'),
						dataType: 'json',
						cache: true,

						data: params =>
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

				$this.select2(config).data(`init.${NAME}`, true);
			});
		},

		/**
		 * Destroy
		 *
		 * @param {String|HTMLElement|jQuery} selector
		 */
		destroy: selector =>
		{
			_$(selector).removeData(`init.${NAME}`).select2('destroy');
		}
	};

	$(window).on(`load.${NAME}`, () => methods.init(SELECTOR));

	return methods;
})($);
