import $ from 'jquery';
import 'select2';

import md from 'Duo/AdminBundle/Resources/webpack/js/util/mobiledetect';

require('select2/dist/css/select2.css');
require('select2-theme-bootstrap4/dist/select2-bootstrap.css');

const locale = window.locale.slice(0, 2);
const locales = require.context('select2/src/js/select2/i18n/', false, /\.js$/);

export default ($ =>
{
	const NAME = 'select2';
	const SELECTOR = `[data-init="${NAME}"]`;

	const DEFAULT = {
		theme: 'bootstrap',
		width: '100%',
		dropdownAutoWidth: true,
		allowClear: true,
		forceOnTouch: false,
		language: (() =>
		{
			const key = `./${locale}.js`;

			if (locales.keys().indexOf(key) === -1)
			{
				return 'en';
			}

			return Object.assign({}, locales(key));
		})()
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
			if ((md.mobile() || md.tablet()) && !options.forceOnTouch)
			{
				return;
			}

			_$(selector).each(function()
			{
				const $this = $(this);

				if ($this.data(`init.${NAME}`))
				{
					return;
				}

				const config = $.extend(true, {}, DEFAULT, {
					tags: $this.data('tags') || false,
					placeholder: $this.data('placeholder') || 'Please choose',
					minimumResultsForSearch: $this.find('option').length <= 10 ? Infinity : 10
				}, options);

				$this.select2(config).data(`init.${NAME}`, true);
			});
		},

		/**
		 * Destroy
		 *
		 * @param {string|HTMLElement|jQuery} selector
		 */
		destroy: selector =>
		{
			_$(selector).removeData(`init.${NAME}`).select2('destroy');
		}
	};

	$(window).on(`load.${NAME}`, () => methods.init(SELECTOR));

	return methods;
})($);
