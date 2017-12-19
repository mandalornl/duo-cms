import $ from 'jquery';

$(() =>
{
	const $document = $(document);

	$document.on('click', '[data-action="delete"]', '[data-action="destroy"]', function(e)
	{
		e.preventDefault();

		// TODO: implement swal/modal
	});

	$document.on('click', '[data-action="undelete"]', function(e)
	{
		e.preventDefault();

		// TODO: implement swal/modal
	});

	$document.on('click', '[data-action="duplicate"]', function(e)
	{
		e.preventDefault();

		// TODO: implement swal/modal
	});
});