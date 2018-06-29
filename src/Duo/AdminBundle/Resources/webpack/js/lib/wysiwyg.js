import $ from 'jquery';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import uniqid from '../util/uniqid';

window.wysiwygEditors = window.wysiwygEditors || {};

/**
 * Initialize wysiwyg editor
 *
 * @param {string|jQuery|HTMLElement} [selector = '.wysiwyg']
 */
const init = (selector = '.wysiwyg') =>
{
	const $selector = (selector instanceof jQuery || 'jquery' in Object(selector)) ? selector : $(selector);

	$selector.each(function()
	{
		const $this = $(this);

		if ($this.data('init.wysiwyg'))
		{
			return;
		}

		$this.data('init.wysiwyg', true);

		ClassicEditor.create(this).then(editor =>
		{
			if ($this.prop('disabled'))
			{
				editor.isReadOnly = true;
			}

			this.id = this.id || uniqid();

			window.wysiwygEditors[this.id] = editor;
		}).catch(error => console.error(error));
	});
};

/**
 * Destroy editor
 *
 * @param {string|jQuery|HTMLElement} [selector = '.wysiwyg']
 */
const destroy = (selector = '.wysiwig') =>
{
	const $selector = (selector instanceof jQuery || 'jquery' in Object(selector)) ? selector : $(selector);

	$selector.each(function()
	{
		const $this = $(this);
		const id = $this.attr('id');

		if (!window.wysiwygEditors[id])
		{
			return;
		}

		$this.removeData('init.wysiwyg');

		window.wysiwygEditors[id].destroy().catch(error => console.error(error));
	});
};

export {init, destroy};