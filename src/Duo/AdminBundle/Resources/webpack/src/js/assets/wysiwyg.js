import $ from 'jquery';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

window.wysiwygEditors = window.wysiwygEditors || {};

/**
 * Initialize wysiwyg editor
 *
 * @param {string|jQuery|HTMLElement} [selector = '.wysiwyg']
 * @param {{}} [globalOptions = {}]
 */
const init = (selector = '.wysiwyg', globalOptions = {}) =>
{
	const $selector = (selector instanceof jQuery || 'jquery' in Object(selector)) ? selector : $(selector);

	$selector.each(function()
	{
		const $this = $(this);
		if ($this.data('initialized.wysiwyg'))
		{
			return;
		}

		const options = $.extend({}, globalOptions);

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