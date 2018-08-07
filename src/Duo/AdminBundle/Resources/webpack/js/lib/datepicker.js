import $ from 'jquery';
import 'bootstrap-datepicker';

require('bootstrap-datepicker/dist/css/bootstrap-datepicker3.css');

/**
 * Initialize date picker
 *
 * @param {{}} [options]
 */
export default (options = {}) =>
{
	options = $.extend({}, {
		selector: '[data-toggle="datepicker"]',
		calendarWeeks: true,
		clearBtn: true,
		format: 'dd-mm-yyyy'
	}, options);

	const $selector = (
		options.selector instanceof jQuery || 'jquery' in Object(options.selector)
	) ? options.selector : $(options.selector);

	$selector.each(function()
	{
		const $this = $(this);

		if ($this.data('init.datepicker'))
		{
			return;
		}

		$this.attr('placeholder', options.format).datepicker(options).data('init.datepicker', true);
	});
};