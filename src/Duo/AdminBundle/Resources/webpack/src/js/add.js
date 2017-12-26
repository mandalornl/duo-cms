import $ from 'jquery';
import 'bootstrap/js/dist/util';
import 'bootstrap/js/dist/collapse';
import 'bootstrap/js/dist/tab';

$(() =>
{
	$(document).on('click', 'button[data-action="save"]', function()
	{
		const $this = $(this);
		$this.prop('disabled', true);

		$(`form[name="${$this.data('type')}_admin"]`).submit();
	});
});