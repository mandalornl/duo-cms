import $ from 'jquery';
import {debounce} from 'lodash';

import './jquery/nestable';
import {get} from './api';
import * as loader from './util/loader';

$(() =>
{
	$('.nestable').nestable({
		onDrop: function(e, data)
		{
			console.log(data);
		},

		onExpand: debounce(async function()
		{
			const $this = $(this);
			const $li = $this.closest('li');

			if ($li.find('> ul').length)
			{
				return;
			}

			loader.show();

			const html = await get($this.data('url'));

			$li.append(html);

			loader.hide();
		}, 250)
	});
});