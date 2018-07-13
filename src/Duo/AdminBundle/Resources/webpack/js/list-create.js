import $ from 'jquery';
import 'bootstrap/js/dist/util';
import 'bootstrap/js/dist/collapse';
import 'bootstrap/js/dist/tab';

import datePicker from 'src/Duo/AdminBundle/Resources/webpack/js/lib/datepicker';
import select2 from 'src/Duo/AdminBundle/Resources/webpack/js/lib/select2';
import autoComplete from 'src/Duo/AdminBundle/Resources/webpack/js/lib/autocomplete';
import partList from 'src/Duo/AdminBundle/Resources/webpack/js/lib/part-list';
import maxLength from 'src/Duo/AdminBundle/Resources/webpack/js/lib/maxlength';
import * as wysiwyg from 'src/Duo/AdminBundle/Resources/webpack/js/lib/wysiwyg';
import * as doNotLeave from 'src/Duo/AdminBundle/Resources/webpack/js/util/donotleave';

$(() =>
{
	const $form = $('.listing-entity-form');

	if (!$form.length)
	{
		return;
	}

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

	$form.on('change', '.translation-list .tab-list > select', function()
	{
		$(this.value).tab('show');
	});

	$('.listing-entity').on('click', 'button[data-action="save"]', function()
	{
		const $this = $(this);
		$this.prop('disabled', true);

		doNotLeave.disable();

		$form.submit();
	});

	const $invalid = $form.find('.is-invalid');

	if ($invalid.length)
	{
		// open any tabs with first invalid field
		let $tabPane = $invalid.first().closest('.tab-pane:not(:visible)');

		do
		{
			const $anchor = $(`[href="#${$tabPane.attr('id')}"]`);
			$anchor.tab('show');

			const $option = $(`option[value="#${$anchor.attr('id')}"]`);
			$option.closest('select').find('option').not($option).prop('selected', false);
			$option.prop('selected', true);

			$tabPane = $tabPane.closest('.tab-content').closest('.tab-pane');
		} while ($tabPane.length);

		// focus first invalid field
		window.setTimeout(() =>
		{
			$invalid.first().focus();
		}, 250);

		// add badge to tab
		$form.find('.tab-pane').each(function()
		{
			const $this = $(this);

			const $invalid = $this.find('.is-invalid');

			if (!$invalid.length)
			{
				return;
			}

			const $anchor = $(`[href="#${$this.attr('id')}"]`);
			$anchor.append(`<span class="badge badge-danger">${$invalid.length}</span>`);
		});

		// HACK: remove duplicate error messages
		$invalid.each(function()
		{
			const messages = [];

			$(this).closest('.form-group').find('.invalid-feedback > span').each(function()
			{
				const $this = $(this);

				if (messages.indexOf($this.text()) !== -1)
				{
					$this.remove();

					return;
				}

				messages.push($this.text());
			});
		});
	}

	datePicker();
	select2();
	autoComplete();
	partList();
	maxLength();

	wysiwyg.init();
});