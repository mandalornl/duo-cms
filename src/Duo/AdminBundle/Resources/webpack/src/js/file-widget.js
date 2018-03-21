import $ from 'jquery';
import {map} from 'lodash';

$(() =>
{
	$(document).on('change', '.custom-file .custom-file-input', function()
	{
		if (!this.value)
		{
			return;
		}

		const $label = $(this).next('.custom-file-label');

		const text = map(this.files, file =>
		{
			return `file://${file.name}`;
		});

		$label.text(text.join(', '));
	});
});