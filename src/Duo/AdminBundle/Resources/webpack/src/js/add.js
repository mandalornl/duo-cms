import $ from 'jquery';
import 'bootstrap/js/dist/util';
import 'bootstrap/js/dist/collapse';
import 'bootstrap/js/dist/tab';

import * as datepicker from './assets/datepicker';
import * as select2 from './assets/select2';
import * as wysiwyg from './assets/wysiwyg';
import * as doNotLeave from './util/donotleave';

$(() =>
{
	const $document = $(document);

	$document.on('click', 'button[data-action="save"]', function()
	{
		const $this = $(this);
		$this.prop('disabled', true);

		doNotLeave.disable();

		$(`form[name="${$this.data('type')}"]`).submit();
	});

	$document.on('change.donotleave', 'select', function()
	{
		doNotLeave.enable();

		$document.off('.donotleave');
	});

	$document.on({
		'keydown.donotleave': function()
		{
			$(this).data('donotleave.keydown', this.value);
		},

		'keyup.donotleave': function()
		{
			const $this = $(this);
			if ($this.data('donotleave.keydown') !== this.value)
			{
				doNotLeave.enable();

				$document.off('.donotleave').removeData('donotleave.keydown');
			}
		}
	}, 'input, textarea');

	datepicker.init();
	select2.init();
	wysiwyg.init();
});