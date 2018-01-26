import $ from 'jquery';

window.jQuery = $;

require('nestable2');

console.log($.isFunction('nestable'));
/**
 * Initialize nestable tree
 *
 * @param {{}} [options = {}]
 * @param {string|jQuery|HTMLElement} [selector = '.nestable-tree']
 */
const init = (options = {}, selector = '.nestable-tree') =>
{
	const $selector = (selector instanceof jQuery || 'jquery' in Object(selector)) ? selector : $(selector);

	$selector.each(function()
	{
		const $this = $(this);

		if ($this.data('initalized.nestable-tree'))
		{
			return;
		}

		const options = $.extend({}, {
			maxDepth: 16,
			scroll: false,
			callback: function(element, e)
			{

			}
		}, options);

		$this.nestable(options).data('initialized.nestable-tree');
	});
};

export {init};