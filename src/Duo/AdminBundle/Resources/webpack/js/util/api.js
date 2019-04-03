import {stringify} from 'querystring';
import {extend} from 'lodash';

/**
 * Call
 *
 * @param {string} method
 *
 * @returns {function(string=, *=): Promise<Response>}
 */
const call = method => (url, data = null) =>
{
	if (method === 'GET')
	{
		url += (data ? `?${stringify(data)}` : '');
	}

	return fetch(url, {
		headers: Object.assign({}, {
			'accept': 'application/json'
		}, data instanceof FormData ? {} : {
			'content-type': 'application/json; charset=utf-8'
		}),
		method: method,
		credentials: 'same-origin',
		body: method === 'GET' ?
			null :
			data instanceof FormData ?
				data :
				JSON.stringify(data)
	}).then(res =>
	{
		if (!res.ok && res.status !== 400)
		{
			throw new Error(`Fetch error, resulted with: ${res.status} (${res.statusText}).`);
		}

		return res.json();
	}).catch(err =>
	{
		console.error(err);

		return null;
	});
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
		onUploadComplete: e => {},
		onUploadProgress: e => {}
	}, options);

	const params = Object.assign({}, options.params, {
		filename: file.name,
		mimeType: file.type,
		size: file.size
	});

	const req = new XMLHttpRequest();
	req.open('PUT', `${url}?${stringify(params)}`);
	req.setRequestHeader('content-type', 'application/octet-stream');
	req.addEventListener('error', reject);
	req.addEventListener('load', () =>
	{
		try
		{
			const res = JSON.parse(req.responseText);

			if (res.error)
			{
				reject(res.error);

				return;
			}

			resolve(res);
		}
		catch (error)
		{
			reject(error);
		}
	});

	req.upload.addEventListener('load', options.onUploadComplete);
	req.upload.addEventListener('progress', options.onUploadProgress);

	req.withCredentials = true;

	req.send(file);
});

module.exports = {
	get: call('GET'),
	post: call('POST'),
	put: call('PUT'),
	delete: call('DELETE'),

	uploadFile
};
