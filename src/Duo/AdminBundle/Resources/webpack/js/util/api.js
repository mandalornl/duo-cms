import {stringify} from 'querystring';

/**
 * Call
 *
 * @param {String} method
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
	}).then(response =>
	{
		if (!response.ok && response.status !== 400)
		{
			throw new Error(`Fetch error, resulted with: ${response.status} (${response.statusText}).`);
		}

		return response.json();
	}).catch(error =>
	{
		console.error(error);

		return null;
	});
};

/**
 * Upload file
 *
 * @param {String} url
 * @param {File} file
 * @param {{}} [options]
 * @param {{}} [params]
 *
 * @returns {Promise<any>}
 */
const uploadFile = (url, file, options = {}, params = {}) => new Promise((resolve, reject) =>
{
	options = Object.assign({}, {
		onUploadProgress: null,
		onUploadComplete: null
	}, options);

	params = Object.assign({}, params, {
		filename: file.name,
		mimeType: file.type,
		size: file.size
	});

	const request = new XMLHttpRequest();
	request.open('PUT', `${url}?${stringify(params)}`);
	request.setRequestHeader('content-type', 'application/octet-stream');
	request.addEventListener('error', reject);
	request.addEventListener('load', () =>
	{
		try
		{
			const result = JSON.parse(request.responseText);

			if (result.error)
			{
				reject(result.error);

				return;
			}

			resolve(result);
		}
		catch (error)
		{
			reject(error);
		}
	});

	if (options.onUploadComplete instanceof Function)
	{
		request.upload.addEventListener('load', options.onUploadComplete);
	}

	if (options.onUploadProgress instanceof Function)
	{
		request.upload.addEventListener('progress', options.onUploadProgress);
	}

	request.withCredentials = true;

	request.send(file);
});

module.exports = {
	get: call('GET'),
	post: call('POST'),
	put: call('PUT'),
	delete: call('DELETE'),

	uploadFile
};
