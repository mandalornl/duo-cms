import $ from 'jquery';

$(() =>
{
	/**
	 * Update backdrop z-index
	 *
	 * @param {jQuery} $item
	 */
	const updateBackdrop = ($item) => setTimeout(() =>
	{
		$('.modal-backdrop:not(.modal-stack)').css('z-index', Number($item.css('z-index')) - 1).addClass('modal-stack');
	}, 0);

	$(document).on({
		'show.bs.modal': function()
		{
			const $this = $(this);

			// add to body
			if (!$this.parent().is(document.body))
			{
				$this.appendTo(document.body);
			}

			// only update backdrop z-index
			if (($this.attr('style') || '').indexOf('z-index') !== -1)
			{
				updateBackdrop($this);
				return;
			}

			$this.css('z-index', 1040 + (10 * $('.modal:visible').length));

			updateBackdrop($this);
		},

		'hidden.bs.modal': () => setTimeout(() =>
		{
			$(document.body).toggleClass('modal-open', $('.modal:visible').length !== 0);
		}, 0)
	}, '.modal');
});