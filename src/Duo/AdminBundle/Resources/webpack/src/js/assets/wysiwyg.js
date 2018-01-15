import $ from 'jquery';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

window.wysiwygEditors = window.wysiwygEditors || {};

/**
 * Initialize wysiwyg editor
 *
 * @param {{}} [options = {}]
 * @param {string|jQuery|HTMLElement} [selector = '.wysiwyg']
 */
const init = (options = {}, selector = '.wysiwyg') =>
{
	const $selector = (selector instanceof jQuery || 'jquery' in Object(selector)) ? selector : $(selector);

	$selector.each(function()
	{
		const $this = $(this);
		if ($this.data('initialized.wysiwyg'))
		{
			return;
		}

		const options = $.extend({}, options);

		ClassicEditor.create(this, options)
			.then(editor =>
			{
				if ($this.prop('disabled'))
				{
					editor.isReadOnly = true;
				}

				window.wysiwygEditors[this.id] = editor;
			})
			.catch(error =>
			{
				console.error(error);
			});
	});
};

/**
 * Destroy editor
 *
 * @param {Number} id
 */
const destroy = (id) =>
{
	if (!window.wysiwygEditors[id])
	{
		return;
	}

	window.wysiwygEditors[id].destroy().catch(error =>
	{
		console.error(error);
	});
};

export {init, destroy};