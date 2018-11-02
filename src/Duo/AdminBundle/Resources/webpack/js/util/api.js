import {stringify} from 'querystring';
import {extend} from 'lodash';

/**
 * Call
 *
 * @param {string} method
 *
 * @returns {Function}
 */
const call = method => async (url, data = null) =>
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
 * @param {{}} [options]
 *
 * @returns {Promise<any>}
 */
const uploadFile = (url, file, options = {}) => new Promise((resolve, reject) =>
{
	options = extend({}, {
		params: {},
		onUploadComplete: (e) => {},
		onUploadProgress: (e) => {}
	}, options);

	const params = extend(options.params, {
		filename: file.name,
		mimeType: file.type,
		size: file.size
	});

	const xhr = new XMLHttpRequest();
	xhr.open('PUT', `${url}?${stringify(params)}`);
	xhr.setRequestHeader('content-type', 'application/octet-stream');
	xhr.upload.addEventListener('load', options.onUploadComplete);
	xhr.upload.addEventListener('progress', options.onUploadProgress);
	xhr.addEventListener('error', reject);
	xhr.addEventListener('load', () =>
	{
		try
		{
			const response = JSON.parse(xhr.responseText);

			if (response.error)
			{
				reject(response.error);

				return;
			}

			resolve(response);
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