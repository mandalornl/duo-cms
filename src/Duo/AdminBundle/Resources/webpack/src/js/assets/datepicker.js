import $ from 'jquery';
import 'bootstrap-datepicker';

/**
 * Initialize datepicker
 *
 * @param {string|jQuery|HTMLElement} [selector = '.datepicker']
 * @param {{}} [options = {}]
 */
const init = (selector = '.datepicker', options = {}) =>
{
	const $selector = (selector instanceof jQuery || 'jquery' in Object(selector)) ? selector : $(selector);

	$selector.each(function()
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

export default init;