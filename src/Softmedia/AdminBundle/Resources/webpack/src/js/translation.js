import $ from 'jquery';

$(() =>
{
	const $container = $('div[id$="_translations"]');
	if (!$container.length)
	{
		return;
	}

	$container.find('> .form-group').each(function()
	{
		const $formGroup = $(this);
		const locale = $formGroup.find('> legend').text().toLowerCase();

		const $translations = $formGroup.find(`div[id$="_translations_${locale}"]`);
		$translations.appendTo($container);

		$translations.find('.form-control, .custom-select').each(function()
		{
			$([
				'<div class="input-group">',
					'<div class="input-group-addon">',
						locale,
					'</div>',
				'</div>'
			].join('')).insertAfter(this).prepend(this);
		});

		$formGroup.remove();
	});

	$container.closest('.form-group').append($container);
	//$container.addClass('col-12'); // uncomment when using horizontal layout
	$container.prevAll().remove();

	$('#toolbar_language').on('change', function()
	{
		$container.find('div[id*="_translations_"]').hide();
		$container.find(`div[id$="_translations_${this.value}"]`).show();
	}).trigger('change');
});