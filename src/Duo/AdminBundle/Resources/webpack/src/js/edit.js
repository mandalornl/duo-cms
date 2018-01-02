import $ from 'jquery';
import {confirm} from "./util/modal";

$(() =>
{
	$(document).on('click', '[data-modal="confirm"]', confirm(function()
	{
		location.href = $(this).attr('href');
	}));
});