import $ from 'jquery';

import './jquery/nestable';
import {post} from './util/api';
import * as loader from './util/loader';

$(async () =>
{
	const $nestable = $('.nestable');

	$nestable.nestable({
		onDrop: async function(e, data)
		{
			const formData = new FormData();
			formData.append('id', data.$item.data('id'));

			if (data.$newList)
			{
				formData.append('parentId', data.$newList.data('id') || null);
			}

			if (data.$sibling)
			{
				formData.append('siblingId', data.$sibling.data('id') || null);
			}

			loader.show(true);

			await post(`${$nestable.data('url')}/json`, formData);

			loader.hide();
		}
	});
});