import $ from 'jquery';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

window.wysiwygEditors = window.wysiwygEditors || {};

/**
 * Initialize wysiwyg editor
 *
 * @param {{}} [options = {}]
 * @param {string} [selector = '.wysiwyg']
 */
const init = (options = {}, selector = '.wysiwyg') =>
{
	$(selector).each(function()
	{
		const $this = $(this);
		if ($this.data('initialized.wysiwyg'))
		{
			return;
		}

		const options = $.extend({}, {
			minHeight: 300
		}, options);

		ClassicEditor.create(this, options)
			.then(editor =>
			{
				window.wysiwygEditors[this.id] = editor;
			})
			.catch(error =>
			{
				console.error(error);
			});
	});
};

export {init};