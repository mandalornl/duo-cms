import $ from 'jquery';

require('./translation');

$(() =>
{
	$(document).on('click', 'button[data-action="save"]', function()
	{
		const $this = $(this);
		$this.prop('disabled', true);

		$(`form[name="${$this.data('type')}_admin"]`).submit();
	});
});