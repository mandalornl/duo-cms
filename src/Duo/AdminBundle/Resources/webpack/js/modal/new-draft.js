import $ from 'jquery';

import doNotLeave from 'Duo/AdminBundle/Resources/webpack/js/util/donotleave';

($ =>
{
	const NAME = 'new-draft';
	const SELECTOR = `.modal.${NAME}`;

	/**
	 * Save
	 *
	 * @param {Event} event
	 */
	const save = function(event)
	{
		event.preventDefault();

		const $this = $(this);
		const $modal = $this.closest('.modal');

		const $required = $modal.find(':input[required]').filter(function()
		{
			return this.value === '';
		}).each(function()
		{
			const $this = $(this);

			$this.addClass('is-invalid');
			$this.closest('.form-group').find('.invalid-feedback').removeClass('d-none');
		});

		if ($required.length)
		{
			return;
		}

		$this.prop('disabled', true);

		doNotLeave.disable();

		const $form = $('.listing-form');
		$form.attr('action', $modal.data('url'));
		$form.append($modal.find(':input:not(button)').clone());
		$form.submit();
	};

	/**
	 * Dismiss
	 *
	 * @param {Event} event
	 */
	const dismiss = function(event)
	{
		event.preventDefault();

		const $modal = $(this).closest('.modal');

		$modal.find(':input').each(function()
		{
			const $this = $(this);

			$this.val(null).removeClass('is-invalid');
			$this.closest('.form-group').find('.invalid-feedback').addClass('d-none');
		});
	};

	$(document)
		.on(`click.${NAME}.save`, `${SELECTOR} button:not([data-dismiss])`, save)
		.on(`click.${NAME}.dismiss`, `${SELECTOR} button[data-dismiss]`, dismiss);
})($);
