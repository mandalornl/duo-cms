import $ from 'jquery';
import {modal} from "./confirm";

$(() =>
{
	const $document = $(document);

	$document.on('click', '[data-action]:not([data-action="save"])', modal((e) =>
	{
		location.href = $(e.target).attr('href');
	}));
});