import $ from 'jquery';
import 'select2';

import md from '../util/mobiledetect';

/**
 * Initialize autocomplete
 *
 * @param {string|jQuery|HTMLElement} [selector = '.autocomplete']
 * @param {{}} [globalOptions = {}]
 */
export default (selector = '.autocomplete', globalOptions = {}) =>
{
	const $selector = (selector instanceof jQuery || 'jquery' in Object(selector)) ? selector : $(selector);

	$selector.each(function()
	{
		const $this = $(this);
		if ($this.data('initialized.autocomplete'))
		{
			return;
		}

		const options = $.extend(true, {}, {
			theme: 'bootstrap',
			width: '100%',
			dropdownAutoWidth: true,
			allowClear: true,
			placeholder: $this.data('placeholder') || 'Enter keyword(s)',
			minimumInputLength: 2,
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
		}, globalOptions);

		$this.select2(options).data('initialized.autocomplete', true);
	});
};