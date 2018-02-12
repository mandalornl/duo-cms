import $ from 'jquery';

/**
 * Initialize max length
 *
 * @param {string|jQuery|HTMLElement} [selector = '[maxlength]']
 */
const init = (selector = '[maxlength]') =>
{
	const $selector = (selector instanceof jQuery || 'jquery' in Object(selector)) ? selector : $(selector);

	$selector.each(function()
	{
		const $this = $(this);

		if ($this.data('initialized.maxlength'))
		{
			return;
		}

		let $inputGroup = $this.closest('.input-group');
		if (!$inputGroup.length)
		{
			$inputGroup = $([
				'<div class="input-group">',
					'<div class="input-group-append">',
						'<span class="input-group-text">',
							$this.attr('maxlength'),
						'</span>',
					'</div>',
				'</div>'
			].join(''));

			$inputGroup.insertAfter($this).prepend($this);
		}

		const $text = $inputGroup.find('.input-group-text');

		$this.on('keyup.maxlength', function()
		{
			$text.text(Math.max(0, $this.attr('maxlength') - (this.value || '').length));
		}).trigger('keyup.maxlength');

		$this.data('initialized.maxlength');
	});
};

export default init;
