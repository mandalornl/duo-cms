import $ from 'jquery';
import {modal} from "./confirm";

//require('bootstrap-datepicker/dist/css/bootstrap-datepicker.css');
//require('bootstrap-datepicker/dist/js/bootstrap-datepicker');

$(() =>
{
	const $document = $(document);

	$document.on('click', '[data-action]:not([data-action="save"])', modal((e) =>
	{
		location.href = $(e.target).attr('href');
	}));
});