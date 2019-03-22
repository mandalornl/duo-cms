import $ from 'jquery';
import {load} from 'recaptcha-v3';
import {get, post} from 'Duo/AdminBundle/Resources/webpack/js/util/api';
import * as loader from 'Duo/AdminBundle/Resources/webpack/js/util/loader';

export default ($ =>
{
	const NAME = 'form';
	const SELECTOR = `.widget-${NAME}`;

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
			_$(selector).each(async function()
			{
				const $this = $(this);

				if ($this.data(`init.${NAME}`))
				{
					return;
				}

				$this.data(`init.${NAME}`, true).addClass('loading');

				const res = await get($this.data('url'));

				$this.html(res.html).removeClass('loading');

				const $recaptchaResponse = $this.find('[id$="g-recaptcha-response"]');

				const recaptcha = await load($recaptchaResponse.data('key'), {
					useRecaptchaNet: $recaptchaResponse.data('use-recaptcha-net'),
					autoHideBadge: $recaptchaResponse.data('auto-hide-badge')
				});
				const token = await recaptcha.execute($this.find('form').attr('name'));

				$recaptchaResponse.val(token);

				$this.on(`submit.${NAME}`, 'form', async function(e)
				{
					e.preventDefault();

					const $form = $(this);
					$form.find('[type=submit]').prop('disabled', true);

					loader.show();

					const res = await post($form.attr('action'), new FormData(this));

					loader.hide();

					if (res.html)
					{
						$this.html(res.html);

						return;
					}

					if (res.message)
					{
						$this.html(res.message);
					}

					if (res.redirect)
					{
						window.setTimeout(() => location.href = res.redirect, 0);
					}
				});
			});
		},

		/**
		 * Destroy
		 * @param {string|HTMLElement|jQuery} selector
		 */
		destroy: selector =>
		{
			_$(selector).removeData(`init.${NAME}`).off(`submit.${NAME}`);
		}
	};

	$(window).on(`load.${NAME}`, () => methods.init(SELECTOR));

	return methods;
})($);
