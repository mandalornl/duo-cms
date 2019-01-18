import $ from 'jquery';
import {map} from 'lodash';

($ =>
{
	$(document).on('change.file', '.custom-file .custom-file-input', function()
	{
		const $label = $(this).next('.custom-file-label');

		if (!this.value)
		{
			$label.text(null);

			return;
		}

		const text = map(this.files, file => `file://${file.name}`);

		$label.text(text.join(', '));
	});
})($);