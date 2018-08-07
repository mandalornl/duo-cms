import $ from 'jquery';
import 'select2';

import md from 'duo/AdminBundle/Resources/webpack/js/util/mobiledetect';

require('select2/dist/css/select2.css');
require('select2-theme-bootstrap4/dist/select2-bootstrap.css');

// TODO: import proper i18n
//import 'select2/dist/js/i18n/nl';

/**
 * Initialize select2
 *
 * @param {{}} [options]
 */
export default (options = {}) =>
{
	if (md.mobile() || md.tablet())
	{
		return;
	}

	options = $.extend(true, {}, {
		selector: '[data-toggle="select2"]',
		theme: 'bootstrap',
		width: '100%',
		dropdownAutoWidth: true,
		allowClear: true
	}, options);

	const $selector = (
		options.selector instanceof jQuery || 'jquery' in Object(options.selector)
	) ? options.selector : $(options.selector);

	$selector.each(function()
	{
		const $this = $(this);

		if ($this.data('init.select2'))
		{
			return;
		}

		const opts = $.extend(true, {}, {
			placeholder: $this.data('placeholder') || 'Please choose',
			minimumResultsForSearch: $this.find('option').length <= 10 ? Infinity : 10
		}, options);

		$this.select2(opts).data('init.select2', true);
	});
};