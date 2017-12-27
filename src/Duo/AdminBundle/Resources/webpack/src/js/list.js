import $ from 'jquery';
import 'bootstrap/js/dist/util';
import 'bootstrap/js/dist/collapse';
import 'bootstrap/js/dist/dropdown';

import './sortable';

$(() =>
{
	$(document).on('click', 'tr[data-url] td:not(:first)', function()
	{
		location.href = $(this).closest('tr').data('url');
	});
});