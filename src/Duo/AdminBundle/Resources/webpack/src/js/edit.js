import $ from 'jquery';
import {confirm} from "./util/modal";

$(() =>
{
	$(document).on('click', '[data-modal="confirm"]', confirm((e) =>
	{
		location.href = $(e.target).attr('href');
	}));
});