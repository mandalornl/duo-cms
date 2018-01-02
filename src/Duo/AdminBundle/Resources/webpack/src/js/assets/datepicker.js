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
			calendarWeeks: true,
			clearBtn: true,
			format: 'dd-mm-yyyy'
		}, options);

		$this.attr('placeholder', options.format).datepicker(options).data('initalized.datepicker', true);
	});
};

export {init};