import $ from 'jquery';

export default ($ =>
{
	const NAME = 'maxlength';
	const SELECTOR = `[${NAME}]`;

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
		 */
		init: selector =>
		{
			_$(selector).each(function()
			{
				const $this = $(this);

				if ($this.data(`init.${NAME}`))
				{
					return;
				}

				$this.data(`init.${NAME}`, true);

				let $inputGroup = $this.closest('.input-group');

				if (!$inputGroup.length)
				{
					$inputGroup = $([
						'<div class="input-group">',
							'<div class="input-group-append">',
								'<span class="input-group-text">',
									$this.attr('maxlength'),
								'</span>',
							'</div>',
						'</div>'
					].join(''));

					$inputGroup.insertAfter($this).prepend($this);
				}

				const $text = $inputGroup.find('.input-group-text');

				$this.on(`keyup.${NAME}`, function()
				{
					$text.text(Math.max(0, $this.attr('maxlength') - (this.value || '').length));
				}).trigger(`keyup.${NAME}`);
			});
		},

		/**
		 * Destroy
		 *
		 * @param {string|HTMLElement|jQuery} selector
		 */
		destroy: selector => _$(selector).removeData(`init.${NAME}`).off(`keyup.${NAME}`)
	};

	$(window).on(`load.${NAME}`, () => methods.init(SELECTOR));

	return methods;
})($);