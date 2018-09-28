import $ from 'jquery';
import 'cropper/dist/cropper.css';

require('cropper');

export default ($ =>
{
	const NAME = 'image-crop';
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
			const $selector = _$(selector);

			(function checkVisibility()
			{
				const $visible = $selector.filter(':visible');

				// initialize visible widgets only
				$visible.each(function()
				{
					const $this = $(this);

					if ($this.data(`init.${NAME}`))
					{
						return;
					}

					$this.data(`init.${NAME}`, true);

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

							$crop.val($crop.val() || '0;0;1;1');

							const values = $crop.val().split(';');

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

					$this.on(`change.${NAME}`, '[id$="_ratio"]', function()
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

				// wait for other widgets to be visible before initializing
				if ($selector.not($visible).length)
				{
					window.setTimeout(checkVisibility, 500);
				}
			})();
		},

		/**
		 * Destroy
		 *
		 * @param {string|HTMLElement|jQuery} selector
		 */
		destroy: selector =>
		{
			const $selector = _$(selector);

			$selector.removeData(`init.${NAME}`);
			$selector.find('[id$="_media"]').off('duo.event.media.selectItem duo.event.media.clearItem');
			$selector.find('[id$="_ratio"]').off(`change.${NAME}`);
			$selector.find('.image-preview img').cropper('destroy');
		}
	};

	$(window).on(`load.${NAME}`, () => methods.init(SELECTOR));

	return methods;
})($);