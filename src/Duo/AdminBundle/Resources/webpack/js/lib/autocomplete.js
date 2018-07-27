import $ from 'jquery';
import 'select2';

import md from 'duo/AdminBundle/Resources/webpack/js/util/mobiledetect';

require('select2/dist/css/select2.css');
require('select2-theme-bootstrap4/dist/select2-bootstrap.css');

// TODO: import proper i18n
//import 'select2/dist/js/i18n/nl';

/**
 * Initialize auto complete
 *
 * @param {{}} [options]
 */
export default (options = {}) =>
{
	options = $.extend(true, {}, {
		selector: '.autocomplete',
		theme: 'bootstrap',
		width: '100%',
		dropdownAutoWidth: true,
		allowClear: true,
		minimumInputLength: 2
	}, options);

	const $selector = (
		options.selector instanceof jQuery || 'jquery' in Object(options.selector)
	) ? options.selector : $(options.selector);

	$selector.each(function()
	{
		const $this = $(this);

		if ($this.data('init.autocomplete'))
		{
			return;
		}

		const opts = $.extend(true, {}, {
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

		$this.select2(opts).data('init.autocomplete', true);
	});
};