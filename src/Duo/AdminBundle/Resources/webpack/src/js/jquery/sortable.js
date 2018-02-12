import $ from 'jquery';

const sortable = require('html5sortable');

const methods = {
	/**
	 * Initialize
	 *
	 * @param {{}} [options]
	 *
	 * @returns {jQuery}
	 */
	init: function(options)
	{
		options = $.extend({}, {
			items: '.sortable-item:not(:disabled):not(.disabled)',
			handle: '.sortable-move'
		}, options);

		return $(sortable(this.get(), options));
	},

	/**
	 * Destroy
	 *
	 * @returns {jQuery}
	 */
	destroy: function()
	{
		sortable(this.get(), 'destroy');
		return this;
	},

	/**
	 * Enable
	 *
	 * @returns {jQuery}
	 */
	enable: function()
	{
		sortable(this.get(), 'enable');
		return this;
	},

	/**
	 * Disable
	 *
	 * @returns {jQuery}
	 */
	disable: function()
	{
		sortable(this.get(), 'disable');
		return this;
	}
};

$.fn.sortable = function(method, options)
{
	if (methods[method])
	{
		return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
	}
	else if (typeof method === 'object' || !method)
	{
		return methods.init.apply(this, arguments);
	}
	else
	{
		$.error(`Method ${method} doesn't exist on jQuery.sortable`);
	}
};