import $ from 'jquery';

($ =>
{
	const NAME = 'checkable';
	const SELECTOR = `.${NAME}`;

	const SELECTOR_THEAD = 'thead input[type=checkbox]:not(:disabled)';
	const SELECTOR_TBODY = 'tbody input[type=checkbox]:not(:disabled)';

	$(document).on(`click.${NAME}`, `${SELECTOR} ${SELECTOR_THEAD}`, function()
	{
		const $this = $(this);
		$this.closest(SELECTOR).find(SELECTOR_TBODY).prop('checked', $this.prop('checked'));
	}).on(`click.${NAME}`, `${SELECTOR} ${SELECTOR_TBODY}`, function()
	{
		const $checkable = $(this).closest(SELECTOR);
		const isChecked = !$checkable.find(`${SELECTOR_TBODY}:not(:checked)`).length;
		$checkable.find(SELECTOR_THEAD).prop('checked', isChecked);
	});
})($);
