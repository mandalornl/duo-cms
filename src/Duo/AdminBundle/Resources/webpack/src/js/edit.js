import $ from 'jquery';
import {confirm} from "./util/modal";

$(() =>
{
	$('.listing-edit').on('click', '[data-modal="confirm"]', confirm(function()
	{
		location.href = $(this).attr('href');
	}));
});