import $ from 'jquery';
import 'bootstrap-datepicker';

/**
 * Initialize datepicker
 *
 * @param {string|jQuery|HTMLElement} [selector = '.datepicker']
 * @param {{}} [globalOptions = {}]
 */
export default (selector = '.datepicker', globalOptions = {}) =>
{
	const $selector = (selector instanceof jQuery || 'jquery' in Object(selector)) ? selector : $(selector);

	$selector.each(function()
	{
		const $this = $(this);

		if ($this.data('initialized.datepicker'))
		{
			return;
		}

		const options = $.extend({}, {
			calendarWeeks: true,
			clearBtn: true,
			format: 'dd-mm-yyyy'
		}, globalOptions);

		$this.attr('placeholder', options.format).datepicker(options).data('initialized.datepicker', true);
	});
};