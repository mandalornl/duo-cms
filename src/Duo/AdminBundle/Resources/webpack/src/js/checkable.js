import $ from 'jquery';

$(() =>
{
	$(document).on('click', '.checkable thead [type=checkbox]:not(:disabled)', function()
	{
		const $this = $(this);
		$this.closest('.checkable').find('tbody [type=checkbox]:not(:disabled)').prop('checked', $this.prop('checked'));
	}).on('click', '.checkable tbody [type=checkbox]', function()
	{
		const $checkable = $(this).closest('.checkable');
		const isChecked = !$checkable.find('tbody [type=checkbox]:not(:checked):not(:disabled)').length;
		$checkable.find('thead [type=checkbox]:not(:disabled)').prop('checked', isChecked);
	});
});