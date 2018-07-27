import $ from 'jquery';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import uniqid from 'duo/AdminBundle/Resources/webpack/js/util/uniqid';

const editors = {};

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

			editors[this.id] = editor;
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

		if (!editors[id])
		{
			return;
		}

		$this.removeData('init.wysiwyg');

		editors[id].destroy().then(() =>
		{
			delete editors[id];
		}).catch(error => console.error(error));
	});
};

export default {
	init: init,
	destroy: destroy
};