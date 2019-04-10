import $ from 'jquery';

import 'bootstrap/js/dist/util';
import 'bootstrap/js/dist/collapse';
import 'bootstrap/js/dist/dropdown';
import 'bootstrap/js/dist/alert';
import 'bootstrap/js/dist/tab';

import 'Duo/AdminBundle/Resources/webpack/js/checkable';
import 'Duo/AdminBundle/Resources/webpack/js/menu';
import 'Duo/AdminBundle/Resources/webpack/js/sticky-navbar';
import 'Duo/AdminBundle/Resources/webpack/js/nestable-tree';

import 'Duo/AdminBundle/Resources/webpack/js/modal/fix';

import 'Duo/AdminBundle/Resources/webpack/js/lib/datepicker';
import 'Duo/AdminBundle/Resources/webpack/js/lib/select2';
import 'Duo/AdminBundle/Resources/webpack/js/lib/maxlength';

import postMessage from 'Duo/AdminBundle/Resources/webpack/js/util/post-message';

postMessage.on(window, event =>
{
	if (event.origin !== location.origin)
	{
		throw new Error('Not allowed');
	}

	try
	{
		const response = typeof event.data === 'string' ? JSON.parse(event.data) : event.data;

		if (response.eventName)
		{
			// trigger target or use window instead
			$(response.target ? `#${response.target}` : window).trigger(response.eventName, response.data);
		}
	}
	catch (error)
	{
		console.error(error);
	}
});
