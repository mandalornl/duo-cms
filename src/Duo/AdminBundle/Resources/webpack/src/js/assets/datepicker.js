import $ from 'jquery';
import 'bootstrap-datepicker';

/**
 * Initialize datepicker
 *
 * @param {{}} [options = {}]
 * @param {string} [selector = '.datepicker']
 */
const init = (options = {}, selector = '.datepicker') =>
{
	$(selector).each(function()
	{
		const $this = $(this);
		if ($this.data('initalized.datepicker'))
		{
			return;
		}

		const options = $.extend({}, {

		}, options);

		$this.datepicker(options).data('initalized.datepicker', true);
	});
};

export {init};