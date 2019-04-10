import $ from 'jquery';
import debounce from 'lodash/debounce';
import md from 'Duo/AdminBundle/Resources/webpack/js/util/mobiledetect';

export default ($ =>
{
	const NAME = 'maxlength';
	const SELECTOR = `[${NAME}]`;

	const wait = (md.mobile() || md.tablet()) ? 300 : 150;

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

				$this.on(`keyup.${NAME}`, debounce(function()
				{
					$text.text(Math.max(0, $this.attr('maxlength') - (this.value || '').length));
				}, wait)).trigger(`keyup.${NAME}`);
			});
		},

		/**
		 * Destroy
		 *
		 * @param {String|HTMLElement|jQuery} selector
		 */
		destroy: selector =>
		{
			_$(selector).removeData(`init.${NAME}`).off(`keyup.${NAME}`);
		}
	};

	$(window).on(`load.${NAME}`, () => methods.init(SELECTOR));

	return methods;
})($);
