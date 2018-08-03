import $ from 'jquery';
import {get} from 'duo/AdminBundle/Resources/webpack/js/util/api';

const modals = {};

/**
 * Initialize
 *
 * @param {string|jQuery|HTMLElement} [selector = '.media-widget']
 */
export default (selector = '.media-widget') =>
{
	const $selector = (selector instanceof jQuery || 'jquery' in Object(selector)) ? selector : $(selector);

	$selector.each(function()
	{
		const $this = $(this);

		if ($this.data('init.media-widget'))
		{
			return;
		}

		$this.data('init.media-widget', true);

		const $media = $this.find('[id$="_media"]');
		const $caption = $this.find('.caption');
		const $preview = $this.find('.image-preview');

		const $btnClear = $this.find('button[data-action="clear"]');

		$this.on('click', 'button[data-action="select"]', async function(e)
		{
			e.preventDefault();

			const $this = $(this);

			let $modal = modals[$this.data('url')];

			if (!$modal)
			{
				const html = await get($this.data('url'));

				$modal = $(html);
				$modal.appendTo('body');

				modals[$this.data('url')] = $modal;
			}

			$modal.modal('show');

			// listen for iframe select event
			$(window).on('duo.event.iframe.select', (e, data) =>
			{
				// TODO: check mediaType

				if (data.type !== 'media')
				{
					return;
				}

				// dispatch clear event before updating fields
				$btnClear.trigger('click');

				/**
				 * Select item
				 */
				const selectItem = () =>
				{
					$media.val(data.id);
					$caption.text(data.name);

					$modal.find('[data-dismiss]').trigger('click');

					$media.trigger('duo.event.media.select');
				};

				// pre load image before selecting item
				if (data.src)
				{
					const $image = $(`<img src="${data.src}" srcset="${data.srcset}" sizes="(min-width: 576px) 66.66667vw, (min-width: 768px) 50vw, 100vw" alt="${data.name}" />`);
					$image.on('load', () =>
					{
						$preview.append($image);

						selectItem();
					});

					return;
				}

				selectItem();
			});
		});

		$this.on('click', 'button[data-action="clear"]', function(e)
		{
			e.preventDefault();

			$caption.text(null);

			$media.val(null);
			$media.trigger('duo.event.media.clear');

			// empty preview after event
			$preview.empty();
		});
	});
};