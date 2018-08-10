import $ from 'jquery';
import doNotLeave from 'duo/AdminBundle/Resources/webpack/js/util/donotleave';
import {uploadFile} from 'duo/AdminBundle/Resources/webpack/js/util/api';

$(() =>
{
	const $upload = $('.media-upload');

	if (!$upload.length)
	{
		return;
	}

	const $view = $upload.find('.listing-view-grid, .listing-view-list');

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
			files = Array.prototype.slice.call(dataTransfer.items).filter(item => item.kind === 'file').map(item => item.getAsFile());

			// cleanup
			dataTransfer.items.clear();

			return files;
		}

		// use DataTransfer interface instead
		files = Array.prototype.slice.call(dataTransfer.files);

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
		const $progressBar = $([
			'<div class="progress fade">',
				'<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">',
					`${file.name} 0%`,
				'</div>',
			'</div>'
		].join('')).appendTo($view);

		window.setTimeout(() => $progressBar.addClass('show'), 0);

		return uploadFile($upload.data('url'), file, {
			onUploadProgress: (e) =>
			{
				if (!e.lengthComputable)
				{
					return;
				}

				const percentage = Math.round(e.loaded / e.total * 100);

				$progressBar.find('.progress-bar')
					.css('width', `${percentage}%`)
					.attr('aria-valuenow', percentage)
					.text(`${file.name} ${percentage}%`);
			},

			onUploadComplete: () =>
			{
				$progressBar.removeClass('show').on('bsTransitionEnd', () =>
				{
					$progressBar.remove();
				}).emulateTransitionEnd(150);
			}
		});
	};

	$upload.on({
		'dragenter': () => $upload.addClass('enter')
	});

	$upload.on({
		'drop': function(e)
		{
			e.preventDefault();

			$upload.removeClass('enter');

			// show warning on page reload
			doNotLeave.enable();

			const files = getFiles(e.originalEvent.dataTransfer);
			const promises = files.map(file => handleUpload(file));

			Promise.all(promises).then(() =>
			{
				doNotLeave.disable();

				location.reload();
			}).catch(err => console.error(err));
		},

		'dragover': (e) => e.preventDefault(),

		'dragleave': () => $upload.removeClass('enter')

	}, '.upload-dropzone');
});