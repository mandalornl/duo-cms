import {stringify} from 'querystring';
import {extend} from 'lodash';

/**
 * Call
 *
 * @param {string} method
 *
 * @returns {function(*=, *=)}
 */
const call = (method) => async (url, data = null) =>
{
	let options = {
		headers: {
			accept: 'application/json'
		},
		credentials: 'same-origin',
		method: method
	};

	switch (method)
	{
		case 'GET':
			url += (data ? `?${stringify(data)}` : '');

			options = extend({}, options, {
				headers: {
					'content-type': 'application/json'
				}
			});
			break;

		default:
			options = extend({}, options, (() =>
			{
				if (data instanceof FormData)
				{
					return {
						body: data
					};
				}

				return {
					headers: {
						'content-type': 'application/json'
					},
					body: data ? JSON.stringify(data) : null
				}
			})());
	}

	try
	{
		const response = await fetch(url, options);

		if (response.status !== 200)
		{
			console.error('Fetch error, resulted with status code: %s', response.status);

			return null;
		}

		const result = (await response.json());

		if (result.error)
		{
			console.error(result.error);

			return null;
		}

		return result;
	}
	catch (err)
	{
		console.error(err);

		return null;
	}
};

/**
 * Upload file
 *
 * @param {string} url
 * @param {File} file
 * @param {Object} [params]
 * @param {Function} [onProgress]
 *
 * @returns {Promise<*>}
 */
const uploadFile = (url, file, params = {}, onProgress = (() => {})) => new Promise((accept, reject) =>
{
	const xhr = new XMLHttpRequest();

	xhr.open('PUT', `${url}?${stringify(extend(params, {
		filename: file.name,
		mimeType: file.type
	}))}`);
	xhr.setRequestHeader('content-type', 'application/octet-stream');
	xhr.upload.addEventListener('progress', onProgress);
	xhr.addEventListener('error', reject);
	xhr.addEventListener('load', () =>
	{
		let parsed = null;

		try
		{
			parsed = JSON.parse(xhr.responseText);

			if (parsed.error)
			{
				reject(parsed.error);
				return;
			}

			accept(parsed);
		}
		catch (err)
		{
			reject(err);
		}
	});

	xhr.send(file);
});

module.exports = {
	get: call('GET'),
	post: call('POST'),
	put: call('PUT'),
	delete: call('DELETE'),

	uploadFile
};