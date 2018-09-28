import $ from 'jquery';

$(() =>
{
	const theadSelector = 'thead input[type=checkbox]:not(:disabled)';
	const tbodySelector = 'tbody input[type=checkbox]:not(:disabled)';

	$(document).on('click.checkable', `.checkable ${theadSelector}`, function()
	{
		const $this = $(this);
		$this.closest('.checkable').find(tbodySelector).prop('checked', $this.prop('checked'));
	}).on('click.checkable', `.checkable ${tbodySelector}`, function()
	{
		const $checkable = $(this).closest('.checkable');
		const isChecked = !$checkable.find(`${tbodySelector}:not(:checked)`).length;
		$checkable.find(theadSelector).prop('checked', isChecked);
	});
});