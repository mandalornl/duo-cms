import $ from 'jquery';
import {debounce} from 'lodash';
import md from 'duo/AdminBundle/Resources/webpack/js/util/mobiledetect';

($ =>
{
	$(document).on('keyup.slug', '[id$="_slug"]', debounce(function()
	{
		const $this = $(this);
		const $url = $this.closest('.tab-pane').find('[id$="_url"]');

		const values = ($url.val() || '/').split('/');
		values.pop();
		values.push($this.val());

		$url.val(values.join('/'));
	}, (md.mobile() || md.tablet()) ? 1000 : 250));
})($);