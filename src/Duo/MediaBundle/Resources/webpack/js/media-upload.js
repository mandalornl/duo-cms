import $ from 'jquery';
import doNotLeave from 'Duo/AdminBundle/Resources/webpack/js/util/donotleave';
import {uploadFile} from 'Duo/AdminBundle/Resources/webpack/js/util/api';

$(() =>
{
	const $container = $('.media-upload');

	if (!$container.length)
	{
		return;
	}

	const $view = $container.find('.listing-view-grid, .listing-view-list');

	/**
	 * Get files
	 *
	 * @param {DataTransferItemList|DataTransfer} dataTransfer
	 *
	 * @returns {File[]}
	 */
	const getFiles = dataTransfer =>
	{
		let files;

		// use DataTransferItemList
		if (dataTransfer.items)
		{
			files = Array.from(dataTransfer.items).filter(item => item.kind === 'file').map(item => item.getAsFile());

			// cleanup
			dataTransfer.items.clear();

			return files;
		}

		// use DataTransfer interface instead
		files = Array.from(dataTransfer.files);

		// cleanup
		dataTransfer.clearData();

		return files;
	};

	/**
	 * Handle upload
	 *
	 * @param {File} file
	 *
	 * @returns {Promise<any>}
	 */
	const handleUpload = file =>
	{
		const $progress = $([
			'<div class="progress fade">',
				'<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">',
					`${file.name} 0%`,
				'</div>',
			'</div>'
		].join('')).prependTo($view);

		window.setTimeout(() => $progress.addClass('show'), 0);

		return uploadFile($container.data('url'), file, {
			onUploadProgress: event =>
			{
				if (!event.lengthComputable)
				{
					return;
				}

				const percentage = Math.round(event.loaded / event.total * 100);

				$progress.find('.progress-bar')
					.css('width', `${percentage}%`)
					.attr('aria-valuenow', percentage)
					.text(`${file.name} ${percentage}%`);
			},

			onUploadComplete: () =>
			{
				$progress.removeClass('show').on('bsTransitionEnd', () =>
				{
					$progress.remove();
				}).emulateTransitionEnd(150);
			}
		});
	};

	$container.on({
		'dragenter': () => $container.addClass('enter')
	});

	$container.on({
		'drop': event =>
		{
			event.preventDefault();

			$container.removeClass('enter');

			// show warning on page reload
			doNotLeave.enable();

			const files = getFiles(event.originalEvent.dataTransfer);
			const promises = files.map(file => handleUpload(file));

			Promise.all(promises).then(() =>
			{
				doNotLeave.disable();

				location.reload();
			}).catch(error => console.error(error));
		},

		'dragover': event => event.preventDefault(),

		'dragleave': () => $container.removeClass('enter')

	}, '.upload-dropzone');
});
