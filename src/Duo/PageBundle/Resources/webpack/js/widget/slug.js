import $ from 'jquery';
import debounce from 'lodash/debounce';

import md from 'Duo/AdminBundle/Resources/webpack/js/util/mobiledetect';

($ =>
{
	const wait = (md.mobile() || md.tablet()) ? 300 : 150;

	$(document).on('keyup.slug', '[id$="_slug"]', debounce(function()
	{
		const $this = $(this);
		const $url = $this.closest('.tab-pane').find('[id$="_url"]');

		const values = ($url.val() || '/').split('/');
		values.pop();
		values.push($this.val());

		$url.val(values.join('/'));
	}, wait));
})($);
