import $ from 'jquery';

($ =>
{
	const NAME = 'checkable';

	const theadSelector = 'thead input[type=checkbox]:not(:disabled)';
	const tbodySelector = 'tbody input[type=checkbox]:not(:disabled)';

	$(document).on(`click.${NAME}`, `.checkable ${theadSelector}`, function()
	{
		const $this = $(this);
		$this.closest('.checkable').find(tbodySelector).prop('checked', $this.prop('checked'));
	}).on(`click.${NAME}`, `.checkable ${tbodySelector}`, function()
	{
		const $checkable = $(this).closest('.checkable');
		const isChecked = !$checkable.find(`${tbodySelector}:not(:checked)`).length;
		$checkable.find(theadSelector).prop('checked', isChecked);
	});
})($);