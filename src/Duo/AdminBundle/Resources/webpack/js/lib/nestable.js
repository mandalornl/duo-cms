import $ from 'jquery';
import sortable from 'html5sortable/dist/html5sortable.es';

import uniqid from 'Duo/AdminBundle/Resources/webpack/js/util/uniqid';

export default ($ =>
{
	const NAME = 'nestable';
	const SELECTOR = `[data-init="${NAME}"]`;

	const DEFAULTS = {
		onSortStart: null,
		onSortStop: null,
		onSortUpdate: null
	};

	/**
	 * Get jQuery
	 *
	 * @param {String|HTMLElement|jQuery} selector
	 *
	 * @returns {jQuery}
	 */
	const _$ = selector => (selector instanceof jQuery || 'jquery' in Object(selector)) ? selector : $(selector);

	const methods = {

		SELECTOR: SELECTOR,

		/**
		 * Initialize
		 *
		 * @param {String|HTMLElement|jQuery} selector
		 * @param {{}} [options]
		 */
		init: (selector, options) =>
		{
			_$(selector).each(function()
			{
				const $this = $(this);

				if ($this.data(`init.${NAME}`))
				{
					return;
				}

				$this.data(`init.${NAME}`, true);

				const config = Object.assign({}, DEFAULTS, options);

				const $list = $this.find('.nestable-list:not(.nestable-async)');

				$list.attr('data-group', $list.data('group') || uniqid());

				sortable($list.get(), {
					items: '.nestable-item:not(:disabled):not(.disabled)',
					handle: '.nestable-handle',
					placeholderClass: 'nestable-placeholder',
					draggingClass: 'nestable-dragging',
					acceptFrom: `[data-group="${$list.data('group')}"]`
				});

				$list.on('sortstart', function(event)
				{
					$list.each(function()
					{
						const $this = $(this);

						if ($this.find('> li').length || $this.closest('.nestable-dragging').length)
						{
							return;
						}

						$this.closest('.nestable-item').addClass('nestable-empty');
					});

					if (config.onSortStart instanceof Function)
					{
						config.onSortStart.call(this, event);
					}
				});

				$list.on('sortstop', function(event)
				{
					$this.find('.nestable-empty').removeClass('nestable-empty');

					if (config.onSortStop instanceof Function)
					{
						config.onSortStop.call(this, event);
					}
				});

				if (config.onSortUpdate instanceof Function)
				{
					$list.on('sortupdate', config.onSortUpdate);
				}
			});
		},

		/**
		 * Destroy
		 *
		 * @param {String|HTMLElement|jquery} selector
		 */
		destroy: selector =>
		{
			const $selector = _$(selector);

			$selector.removeData(`init.${NAME}`);
			$selector.find('.nestable-list').removeAttr('data-group').off('sortstart sortstop sortupdate');

			sortable($selector.get(), 'destroy');
		}
	};

	$(window).on(`load.${NAME}`, () => methods.init(SELECTOR));

	return methods;
})($);
