import $ from 'jquery';

import 'bootstrap/js/dist/util';
import 'bootstrap/js/dist/collapse';
import 'bootstrap/js/dist/dropdown';
import 'bootstrap/js/dist/alert';

import 'duo/AdminBundle/Resources/webpack/js/checkable';
import 'duo/AdminBundle/Resources/webpack/js/menu';
import 'duo/AdminBundle/Resources/webpack/js/sticky-navbar';
import 'duo/AdminBundle/Resources/webpack/js/modal-fix';
import 'duo/AdminBundle/Resources/webpack/js/nestable-tree';

import 'duo/AdminBundle/Resources/webpack/js/lib/datepicker';
import 'duo/AdminBundle/Resources/webpack/js/lib/select2';

import postMessage from 'duo/AdminBundle/Resources/webpack/js/lib/post-message';

postMessage.on(window, e =>
{
	if (e.origin !== location.origin)
	{
		throw new Error('Not allowed');
	}

	try
	{
		const response = typeof e.data === 'string' ? JSON.parse(e.data) : e.data;

		if (response.event)
		{
			// trigger target or use window instead
			$(response.target ? `#${response.target}` : window).trigger(response.event, response.data);
		}
	}
	catch (err)
	{
		console.error(err);
	}
});