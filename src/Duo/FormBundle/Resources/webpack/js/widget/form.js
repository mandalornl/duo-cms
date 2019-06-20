import $ from 'jquery';
import { load } from 'recaptcha-v3';

import { get, post } from 'Duo/AdminBundle/Resources/webpack/js/util/api';
import loader from 'Duo/AdminBundle/Resources/webpack/js/util/loader';

export default ($ =>
{
	const NAME = 'form';
	const SELECTOR = `.widget-${NAME}`;

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
			_$(selector).each(async function()
			{
				const $this = $(this);

				if ($this.data(`init.${NAME}`))
				{
					return;
				}

				$this.data(`init.${NAME}`, true).addClass('loading');

				const response = await get($this.data('url'));

				$this.html(response.html).removeClass('loading');

				const $recaptchaResponse = $this.find('[id$="g-recaptcha-response"]');

				const recaptcha = await load($recaptchaResponse.data('key'), {
					useRecaptchaNet: $recaptchaResponse.data('use-recaptcha-net'),
					autoHideBadge: $recaptchaResponse.data('auto-hide-badge')
				});
				const token = await recaptcha.execute($this.find('form').attr('name'));

				$recaptchaResponse.val(token);

				$this.on(`submit.${NAME}`, 'form', async function(event)
				{
					event.preventDefault();

					const $form = $(this);
					$form.find('[type=submit]').prop('disabled', true);

					loader.show();

					const response = await post($form.attr('action'), new FormData(this));

					loader.hide();

					if (response.html)
					{
						$this.html(response.html);

						return;
					}

					if (response.message)
					{
						$this.html(response.message);
					}

					if (response.redirect)
					{
						window.setTimeout(() => location.href = response.redirect, 0);
					}
				});
			});
		},

		/**
		 * Destroy
		 * @param {String|HTMLElement|jQuery} selector
		 */
		destroy: selector =>
		{
			_$(selector).removeData(`init.${NAME}`).off(`submit.${NAME}`);
		}
	};

	$(window).on(`load.${NAME}`, () => methods.init(SELECTOR));

	return methods;
})($);
