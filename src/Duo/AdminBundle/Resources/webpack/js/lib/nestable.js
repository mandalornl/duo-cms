import $ from 'jquery';
import sortable from 'html5sortable/dist/html5sortable.es';

import uniqid from 'Duo/AdminBundle/Resources/webpack/js/util/uniqid';

export default ($ =>
{
	const NAME = 'nestable';
	const SELECTOR = `[data-init="${NAME}"]`;

	const DEFAULTS = {
		onSortStart: e => {},
		onSortStop: e => {},
		onSortUpdate: e => {}
	};

	/**
	 * Get jQuery
	 *
	 * @param {string|HTMLElement|jQuery} selector
	 *
	 * @returns {jQuery}
	 */
	const _$ = selector => (selector instanceof jQuery || 'jquery' in Object(selector)) ? selector : $(selector);

	const methods = {

		SELECTOR: SELECTOR,

		/**
		 * Initialize
		 *
		 * @param {string|HTMLElement|jQuery} selector
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

				$list.on('sortstart', e =>
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

					config.onSortStart(e);
				});

				$list.on('sortstop', e =>
				{
					$this.find('.nestable-empty').removeClass('nestable-empty');

					config.onSortStop(e);
				});

				$list.on('sortupdate', config.onSortUpdate);
			});
		},

		/**
		 * Destroy
		 *
		 * @param {string|HTMLElement|jquery} selector
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
