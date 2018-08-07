import $ from 'jquery';
import 'bootstrap/js/dist/util';
import 'bootstrap/js/dist/collapse';
import 'bootstrap/js/dist/tab';

import datePicker from 'duo/AdminBundle/Resources/webpack/js/lib/datepicker';
import select2 from 'duo/AdminBundle/Resources/webpack/js/lib/select2';
import autoComplete from 'duo/AdminBundle/Resources/webpack/js/lib/autocomplete';
import maxLength from 'duo/AdminBundle/Resources/webpack/js/lib/maxlength';
import wysiwyg from 'duo/AdminBundle/Resources/webpack/js/lib/wysiwyg';
import doNotLeave from 'duo/AdminBundle/Resources/webpack/js/util/donotleave';

import partWidget from 'duo/PartBundle/Resources/webpack/js/part-widget';
import mediaWidget from 'duo/MediaBundle/Resources/webpack/js/media-widget';
import imageCrop from 'duo/MediaBundle/Resources/webpack/js/image-crop-widget';

$(() =>
{
	const $form = $('.listing-create-form');

	if (!$form.length)
	{
		return;
	}

	// show warning when user closes or reloads page
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

	// select proper translation tab
	$form.on('change', '.translation-list .tab-list > select', function()
	{
		$(this.value).tab('show');
	});

	// init widgets inside part widget
	$form.on('duo.event.part.add', '.part-widget .part-widget-item', function()
	{
		const $item = $(this);

		autoComplete({
			selector: $item.find('[data-toggle="autocomplete"]')
		});

		mediaWidget($item.find('[data-toggle="media"]'));

		imageCrop.init($item.find('[data-toggle="image-crop"]'));
		wysiwyg.init($item.find('[data-toggle="wysiwyg"]'));
	});

	// destroy widgets inside part widget
	$form.on('duo.event.part.remove', '.part-widget .part-widget-item', function()
	{
		const $item = $(this);

		wysiwyg.destroy($item.find('[data-toggle="wysiwyg"]'));
		imageCrop.destroy($item.find('[data-toggle="image-crop"]'));
	});

	// handle form submit
	$('.listing-create').on('click', 'button[data-action="save"]', function()
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
	}

	datePicker();
	select2();
	autoComplete();
	maxLength();

	partWidget();
	mediaWidget();

	imageCrop.init();
	wysiwyg.init();
});