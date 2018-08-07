import $ from 'jquery';
import 'cropper/dist/cropper.css';

require('cropper');

/**
 * Initialize image crop
 *
 * @param {string|jQuery|HTMLElement} [selector]
 */
const init = (selector = '[data-toggle="image-crop"]') =>
{
	const $selector = (selector instanceof jQuery || 'jquery' in Object(selector)) ? selector : $(selector);

	$selector.each(function()
	{
		const $this = $(this);

		if ($this.data('init.image-crop-widget'))
		{
			return;
		}

		$this.data('init.image-crop-widget', true);

		let $image;

		const $media = $this.find('[id$="_media"]');
		const $crop = $this.find('[id$="_crop"]');
		const $ratio = $this.find('[id$="_ratio"]');

		const $preview = $this.find('.image-preview');

		/**
		 * Initialize cropper
		 */
		const init = () =>
		{
			const ratio = ($ratio.val() || '1:1').split(':');

			$image = $preview.find('img');

			$image.cropper({
				viewMode: 1,
				aspectRatio: ratio[0] / ratio[1],
				background: false,
				responsive: true,
				autoCrop: true,
				autoCropArea: 1,
				dragMode: 'none',
				movable: false,
				rotatable: false,
				scalable: false,
				zoomable: false,
				modal: false
			});

			$image.on('ready', () =>
			{
				const imageData = $image.cropper('getImageData');

				const values = ($crop.val() || '0;0;1;1').split(';');

				const x = values[0] * imageData.naturalWidth;
				const y = values[1] * imageData.naturalHeight;

				$image.cropper('setData', {
					x: x,
					y: y,
					width: (values[2] * imageData.naturalWidth) - x,
					height: (values[3] * imageData.naturalHeight) - y
				});
			});

			$image.on('cropend', () =>
			{
				const data = $image.cropper('getData');
				const imageData = $image.cropper('getImageData');

				const x = Math.max(0, Math.min(1, data.x / imageData.naturalWidth));
				const y = Math.max(0, Math.min(1, data.y / imageData.naturalHeight));

				$crop.val([
					x,
					y,
					Math.max(0, Math.min(1, (data.width / imageData.naturalWidth) + x)),
					Math.max(0, Math.min(1, (data.height / imageData.naturalHeight) + y))
				].join(';'));
			});
		};

		$media.on('duo.event.media.selectItem', () => init());

		$media.on('duo.event.media.clearItem', () =>
		{
			$crop.val(null);

			if (!$image)
			{
				return;
			}

			$image.cropper('destroy');
			$image = null;
		});

		$this.on('change', '[id$="_ratio"]', function()
		{
			if (!$image)
			{
				return;
			}

			const ratio = (this.value || '1:1').split(':');

			$image.cropper('setAspectRatio', ratio[0] / ratio[1]);
			$image.trigger('cropend');
		});

		window.setTimeout(() => init(), 0);
	});
};

/**
 * Destroy image crop
 *
 * @param {string|jQuery|HTMLElement} [selector]
 */
const destroy = (selector = '[data-toggle="image-crop"]') =>
{
	const $selector = (selector instanceof jQuery || 'jquery' in Object(selector)) ? selector : $(selector);

	$selector.removeData('init.image-crop-widget');
	$selector.find('.image-preview img').cropper('destroy');
};

export default {
	init: init,
	destroy: destroy
};