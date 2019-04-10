import $ from 'jquery';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import uniqid from 'Duo/AdminBundle/Resources/webpack/js/util/uniqid';

export default ($ =>
{
	const NAME = 'wysiwyg';
	const SELECTOR = `[data-init="${NAME}"]`;

	const editors = {};

	/**
	 * Get jQuery
	 *
	 * @param {String|HTMLElement|jQuery} selector
	 *
	 * @returns {jQuery}
	 */
	const _$ = selector => (selector instanceof jQuery || 'jquery' in Object(selector)) ? selector : $(selector);

	const methods = {

		SELECTOR: SELECTOR,

		/**
		 * Initialize
		 *
		 * @param {String|HTMLElement|jQuery} selector
		 */
		init: selector =>
		{
			_$(selector).each(function()
			{
				const $this = $(this);

				if ($this.data(`init.${NAME}`))
				{
					return;
				}

				$this.data(`init.${NAME}`, true);

				ClassicEditor.create(this, {
					removePlugins: [
						'EasyImage', 'Image', 'ImageCaption',
						'ImageStyle', 'ImageToolbar', 'ImageUpload',
						'MediaEmbed', 'CKFinderUploadAdapter'
					],
					toolbar: [
						'heading', '|',
						'bold', 'italic',
						'bulletedList', 'numberedList',
						'link', 'insertTable', 'blockQuote',
						'undo', 'redo'
					]
				}).then(editor =>
				{
					if ($this.prop('disabled'))
					{
						editor.isReadOnly = true;
					}

					this.id = this.id || uniqid();

					editors[this.id] = editor;

				}).catch(error => console.error(error));
			});
		},

		/**
		 * Destroy
		 *
		 * @param {String|HTMLElement|jQuery} selector
		 */
		destroy: selector =>
		{
			_$(selector).each(function()
			{
				const id = this.id;

				if (!editors[id])
				{
					return;
				}

				const $this = $(this);

				$this.removeData(`init.${NAME}`);

				editors[id].destroy().then(() =>
				{
					delete editors[id];
				}).catch(error => console.error(error));
			});
		}
	};

	$(window).on(`load.${NAME}`, () => methods.init(SELECTOR));

	return methods;
})($);
