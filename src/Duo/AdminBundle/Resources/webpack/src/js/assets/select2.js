import $ from 'jquery';
import 'select2';

import {md} from '../util/mobiledetect';

// TODO: import proper i18n
//import 'select2/dist/js/i18n/nl';

/**
 * Initialize select2
 *
 * @param {{}} [options = {}]
 * @param {string|jQuery|HTMLElement} [selector = '.select2']
 */
const init = (options = {}, selector = '.select2') =>
{
	if (md.mobile() || md.tablet())
	{
		return;
	}

	const $selector = (selector instanceof jQuery || 'jquery' in Object(selector)) ? selector : $(selector);

	$selector.each(function()
	{
		const $this = $(this);
		if ($this.data('initalized.select2'))
		{
			return;
		}

		const options = $.extend({}, {
			theme: 'bootstrap',
			width: '100%',
			dropdownAutoWidth: true,
			allowClear: true,
			placeholder: $this.data('placeholder') || 'Please choose',
			minimumResultsForSearch: $this.find('option').length <= 10 ? Infinity : 10
		}, options);

		$this.select2(options).data('initalized.select2', true);
	});
};

export {init};