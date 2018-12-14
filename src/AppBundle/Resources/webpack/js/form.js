import $ from 'jquery';
import {get, post} from 'duo/AdminBundle/Resources/webpack/js/util/api';
import * as loader from 'duo/AdminBundle/Resources/webpack/js/util/loader';

$(() =>
{
	$('.form-pp[data-url]').each(async function()
	{
		const $this = $(this);
		$this.addClass('loading');

		const res = await get($this.data('url'));

		$this.html(res.html).removeClass('loading');

		$this.on('submit', 'form', async function(e)
		{
			e.preventDefault();

			const $form = $(this);
			$form.find('[type="submit"]').prop('disabled', true);

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

			if (res.url)
			{
				window.setTimeout(() => location.href = res.url, 0);
			}
		});
	});
});
