import $ from 'jquery';
import 'bootstrap/js/dist/util';
import 'bootstrap/js/dist/collapse';
import 'bootstrap/js/dist/tab';

import * as datePicker from './assets/datepicker';
import * as select2 from './assets/select2';
import * as wysiwyg from './assets/wysiwyg';
import * as doNotLeave from './util/donotleave';
import * as pageParts from './util/pageparts';

$(() =>
{
	const $form = $('.form-add, .form-edit');

	$form.on('change.donotleave', 'select', function()
	{
		doNotLeave.enable();

		$form.off('.donotleave');
	});

	$form.on({
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

				$form.off('.donotleave').removeData('donotleave.keydown');
			}
		}
	}, 'input, textarea');

	$('.listing-add, .listing-edit').on('click', 'button[data-action="save"]', function()
	{
		const $this = $(this);
		$this.prop('disabled', true);

		doNotLeave.disable();

		$form.submit();
	});

	datePicker.init();
	select2.init();
	wysiwyg.init();
	pageParts.init();
});